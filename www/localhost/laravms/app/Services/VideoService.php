<?php

namespace App\Services;

use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Format\Video\X264;
use Illuminate\Support\Facades\App;
use App\Models\ClipLog;
use App\Models\UploadLog;
use App\Models\CombineLog;
use App\Models\Admin;
use Flow\Config as FlowConfig;
use Flow\Request as FlowRequest;
use Flow\Basic as FlowBasic;

class VideoService extends BaseService
{
    public function upload($params)
    {
        $ret = ['state' => true];
        $tmpDir = storage_path('app').'/tmp';
        $uploadDir = storage_path('app').'/upload/'.date('Ymd');
        if (!file_exists($tmpDir)) {
            mkdir($tmpDir, '0777', true);
        }
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, '0777', true);
        }

        $config = new FlowConfig();
        $config->setTempDir($tmpDir);

        $newPar = [];
        foreach ($params as $k => $v) {
            $newKey = 'flow'.ucfirst($k);
            $newPar[$newKey] = $v;
        }
        $request = new FlowRequest($newPar);
        $uploadFileName = uniqid()."_".$request->getFileName();
        $uploadPath = $uploadDir.'/'.$uploadFileName;
        $res = FlowBasic::save($uploadPath, $config, $request);
        if ($res) {
            $config = config('ffmpeg');
            $log = App::make('log');
            $ffp = FFProbe::create($config, $log);
            $format = $ffp->format($uploadPath);
            $video = $ffp->streams($uploadPath)->videos()->first();
            $audio = $ffp->streams($uploadPath)->audios()->first();

            $model = new UploadLog();

            $model->title = $params['filename'];
            $model->video = str_replace(storage_path('app'), '', $uploadPath);
            $model->admin_id = $params['admin_id'];
            $model->created_at = date('Y-m-d H:i:s');
            $model->updated_at = date('Y-m-d H:i:s');

            if ($format) {
                $model->duration = floor($format->get('duration'));
                $model->size = $format->get('size');
                $model->bit_rate = $format->get('bit_rate');
            }

            if ($video) {
                $model->width = $video->get('width');
                $model->height = $video->get('height');
                $model->video_codec = $video->get('codec_name');
            }

            if ($audio) {
                $model->audio_codec = $audio->get('codec_name');
                $model->sample_rate = $audio->get('sample_rate');
                $model->channels = $audio->get('channels');
                $model->channel_layout = $audio->get('channel_layout');
            }

            $thumbPath = $uploadPath.'.jpg';
            $ffm = FFMpeg::create($config, $log);
            $source = $ffm->open($uploadPath);
            $source->frame(TimeCode::fromSeconds(1))->save($thumbPath);
            $model->thumb = str_replace(storage_path('app'), '', $thumbPath);

            $res = $model->save();
            if ($res) {
                $this->syncSearch('upload_logs', $model);
            }
        }
        return $ret;
    }

    public function listVideo($params, $type)
    {
        $page = isset($params['page']) ? $params['page'] : 1;
        $pageSize = isset($params['pageSize']) ? $params['pageSize'] : 10;
        $keyword = isset($params['keyword']) ? $params['keyword'] : '';
        $share = isset($params['share']) ? $params['share'] : 0;

        switch ($type) {
            case 1:
                $index = 'upload_logs';
                break;
            case 2:
                $index = 'clip_logs';
                break;
            default:
                $index = 'combine_logs';
        }

        $must = [];
        if ($share && $index != 'combine_logs') {
            $must[] = [
                'bool' => [
                    'must_not' => [
                        ['term' => ['admin_id' => $params['admin_id']]]
                    ]
                ]
            ];
        } else {
            $must[] = ['term' => ['admin_id' => $params['admin_id']]];
        }
        $should = [];
        $title = [];
        $tags = [];
        $sort = [
            ['id' => 'desc']
        ];
        if ($keyword) {
            $terms = array_filter(explode(' ', $keyword));
            if ($terms) {
                foreach ($terms as $v) {
                    $title[] = ['term' => ['title' => $v]];
                }
                if ($type == 2) {
                    foreach ($terms as $v) {
                        $tags[] = ['term' => ['tags' => $v]];
                    }
                }
            }
        }
        if ($title) {
            $should[] = [
                'bool' => [
                    'must' => $title
                ]
            ];
        }
        if ($tags) {
            $should[] = [
                'bool' => [
                    'must' => $tags
                ]
            ];
        }
        if ($should) {
            $must[] = [
                'bool' => [
                    'should' => $should
                ]
            ];
        }

        $builder = \Elasticsearch\ClientBuilder::create()->setHosts(config('search.elasticsearch.hosts'));
        $builder->setLogger(app('log'));
        $es = $builder->build();

        $query = [
            'index' => $index,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => $must
                    ]
                ],
                'sort' => $sort,
                'from' => ($page - 1) * $pageSize,
                'size' => $pageSize
            ]
        ];
        //echo json_encode($query);exit;
        $res = $es->search($query);

        $total = 0;
        $items = [];
        $adminIds = [];

        if (isset($res['hits']) && isset($res['hits']['total']) && isset($res['hits']['total']['value'])) {
            $total = $res['hits']['total']['value'];
            foreach ($res['hits']['hits'] as $v) {
                $items[] = $v['_source'];
                $adminIds[] = $v['_source']['admin_id'];
            }
        }

        $adminInfo = [];
        if ($adminIds) {
            $rows = Admin::query()->whereIn('id', $adminIds)->get();
            $adminInfo = $rows->keyBy('id');
        }

        $domain = config('extra.file_domain');
        if ($type == 2) {
            foreach ($items as &$v) {
                if (isset($adminInfo[$v['admin_id']])) {
                    $v['admin_name'] = $adminInfo[$v['admin_id']]['name'];
                } else {
                    $v['admin_name'] = '';
                }
                $v['thumb'] = $domain . $v['thumb'];
                $v['video'] = $domain . $v['video'];
                $v['duration'] = $this->second2time($v['duration']);
                $v['created_at'] = date('Y-m-d H:i:s', $v['created_at']);
                $v['tags'] = implode(',', $v['tags']);
            }
        } else {
            foreach ($items as &$v) {
                if (isset($adminInfo[$v['admin_id']])) {
                    $v['admin_name'] = $adminInfo[$v['admin_id']]['name'];
                } else {
                    $v['admin_name'] = '';
                }
                $v['thumb'] = $domain . $v['thumb'];
                $v['video'] = $domain . $v['video'];
                $v['duration'] = $this->second2time($v['duration']);
                $v['created_at'] = date('Y-m-d H:i:s', $v['created_at']);
            }
        }

        $send = [
            'state' => true,
            'data' => [
                'total' => $total,
                'items' => $items
            ]
        ];
        return $send;
    }

    public function clipVideo($params)
    {
        $send = ['state' => false];
        $id = isset($params['upload_id']) ? $params['upload_id'] : '';
        if (!$id) {
            $send['error'] = '传参有误';
            return $send;
        }
        $row = UploadLog::query()->where('id', $id)->first();
        if (!$row) {
            $send['error'] = '传参有误';
            return $send;
        }
        $videoOrigin = storage_path('app').$row->video;

        $clipDir = storage_path('app').'/clip/'.date('Ymd');
        if (!file_exists($clipDir)) {
            mkdir($clipDir, '0777', true);
        }
        list($usec, $sec) = explode(" ", microtime());
        $file = $sec . substr($usec, 2) . '.mp4';
        $videoPath = $clipDir.'/'. $file;

        $config = config('ffmpeg');
        $log = App::make('log');
        $ffm = FFMpeg::create($config, $log);
        $video = $ffm->open($videoOrigin);
        $clip = $video->clip(TimeCode::fromSeconds($params['in']), TimeCode::fromSeconds($params['out']));
        $clip->save(new X264(), $videoPath);

        $ffp = FFProbe::create($config, $log);
        $format = $ffp->format($videoPath);
        $video = $ffp->streams($videoPath)->videos()->first();
        $audio = $ffp->streams($videoPath)->audios()->first();

        $model = new ClipLog();

        $model->upload_id = $params['upload_id'];
        $model->in = $params['in'];
        $model->out = $params['out'];
        $model->tags = $params['tags'];
        $model->title = $params['title'];
        $model->video = str_replace(storage_path('app'), '', $videoPath);
        $model->admin_id = $params['admin_id'];
        $model->created_at = date('Y-m-d H:i:s');
        $model->updated_at = date('Y-m-d H:i:s');

        if ($format) {
            $model->duration = floor($format->get('duration'));
            $model->size = $format->get('size');
            $model->bit_rate = $format->get('bit_rate');
        }

        if ($video) {
            $model->width = $video->get('width');
            $model->height = $video->get('height');
            $model->video_codec = $video->get('codec_name');
        }

        if ($audio) {
            $model->audio_codec = $audio->get('codec_name');
            $model->sample_rate = $audio->get('sample_rate');
            $model->channels = $audio->get('channels');
            $model->channel_layout = $audio->get('channel_layout');
        }

        $thumbPath = $videoPath.'.jpg';
        $source = $ffm->open($videoPath);
        $source->frame(TimeCode::fromSeconds($params['in']))->save($thumbPath);
        $model->thumb = str_replace(storage_path('app'), '', $thumbPath);

        $res = $model->save();
        if ($res) {
            $this->syncSearch('clip_logs', $model);
        }

        $send = [
            'state' => true
        ];
        return $send;
    }

    public function combineClip($params)
    {
        $send = ['state' => false];
        $title = isset($params['title']) ? $params['title'] : '';
        if (!$title) {
            $send['error'] = '传参有误';
            return $send;
        }
        $ids = isset($params['ids']) ? $params['ids'] : [];
        if (!$ids) {
            $send['error'] = '传参有误';
            return $send;
        }
        $ids = array_unique($ids);

        $rows = ClipLog::query()->whereIn('id', $ids)->get();
        if (count($rows) != count($ids)) {
            $send['error'] = '传参有误';
            return $send;
        }
        $videos = $rows->pluck('video')->toArray();
        $videoFiles = [];
        foreach ($videos as $v) {
            $v = storage_path('app').$v;
            $videoFiles[] = $v;
        }

        $combineDir = storage_path('app').'/combine/'.date('Ymd');
        if (!file_exists($combineDir)) {
            mkdir($combineDir, '0777', true);
        }
        list($usec, $sec) = explode(" ", microtime());
        $file = $sec . substr($usec, 2) . '.mp4';
        $videoPath = $combineDir.'/'. $file;

        $config = config('ffmpeg');
        $log = App::make('log');
        $ffm = FFMpeg::create($config, $log);
        $video = $ffm->open($videoFiles[0]);
        $video->concat($videoFiles)->saveFromSameCodecs($videoPath, true);

        $ffp = FFProbe::create($config, $log);
        $format = $ffp->format($videoPath);
        $video = $ffp->streams($videoPath)->videos()->first();
        $audio = $ffp->streams($videoPath)->audios()->first();

        $model = new CombineLog();

        $model->clip_ids = implode(',', $params['ids']);
        $model->title = $params['title'];
        $model->video = str_replace(storage_path('app'), '', $videoPath);
        $model->admin_id = $params['admin_id'];
        $model->created_at = date('Y-m-d H:i:s');
        $model->updated_at = date('Y-m-d H:i:s');

        if ($format) {
            $model->duration = floor($format->get('duration'));
            $model->size = $format->get('size');
            $model->bit_rate = $format->get('bit_rate');
        }

        if ($video) {
            $model->width = $video->get('width');
            $model->height = $video->get('height');
            $model->video_codec = $video->get('codec_name');
        }

        if ($audio) {
            $model->audio_codec = $audio->get('codec_name');
            $model->sample_rate = $audio->get('sample_rate');
            $model->channels = $audio->get('channels');
            $model->channel_layout = $audio->get('channel_layout');
        }

        $thumbPath = $videoPath.'.jpg';
        $source = $ffm->open($videoPath);
        $source->frame(TimeCode::fromSeconds(1))->save($thumbPath);
        $model->thumb = str_replace(storage_path('app'), '', $thumbPath);

        $res = $model->save();
        if ($res) {
            $this->syncSearch('combine_logs', $model);
        }

        $send = [
            'state' => true
        ];
        return $send;
    }

    public function syncSearch($index, $row)
    {
        $builder = \Elasticsearch\ClientBuilder::create()->setHosts(config('search.elasticsearch.hosts'));
        $builder->setLogger(app('log'));
        $es = $builder->build();

        $body = [
            'id' => $row->id,
            'title' => $row->title,
            'duration' => $row->duration,
            'width' => $row->width,
            'height' => $row->height,
            'thumb' => $row->thumb,
            'video' => $row->video,
            'admin_id' => $row->admin_id,
            'created_at' => strtotime($row->created_at)
        ];
        if ($index == 'clip_logs') {
            $body['tags'] = explode(',', $row->tags);
            $record = [
                'index' => $index,
                'id' => $row->id,
                'body' => $body
            ];
        } else {
            $record = [
                'index' => $index,
                'id' => $row->id,
                'body' => $body
            ];
        }
        $es->index($record);
    }
}
