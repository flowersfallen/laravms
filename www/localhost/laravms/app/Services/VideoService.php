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
    protected static $errors = [
        'save_error' => '保存失败',
        'del_error' => '删除失败',
        'ids_error' => '传参ids有误',
        'row_not_exist' => '未查到对应记录',
        'no_right' => '无权操作'
    ];

    protected static $fields = [
        'title',
        'tags',
        'is_sharing'
    ];

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
            $model->is_sharing = 0;

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
        if ($share) {
            $must[] = [
                'bool' => [
                    'must_not' => [
                        ['term' => ['admin_id' => $params['admin_id']]]
                    ]
                ]
            ];
            $must[] = ['term' => ['is_sharing' => 1]];
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
                    $title[] = ['match' => ['title' => $v]];
                }
                foreach ($terms as $v) {
                    $tags[] = ['match_phrase' => ['tags' => $v]];
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
        $model->is_sharing = 0;

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
        $model->is_sharing = 0;

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

    public function syncSearch($index, $row, $action = 'index')
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
            'created_at' => strtotime($row->created_at),
            'tags' => explode(',', $row->tags),
            'is_sharing' => $row->is_sharing
        ];
        switch ($action) {
            case 'update':
                $record = [
                    'index' => $index,
                    'id' => $row->id,
                    'body' => [
                        'doc' => $body
                    ]
                ];
                $es->update($record);
                break;
            case 'del':
                $record = [
                    'index' => $index,
                    'id' => $row->id
                ];
                $es->delete($record);
                break;
            default:
                $record = [
                    'index' => $index,
                    'id' => $row->id,
                    'body' => $body
                ];
                $es->index($record);
        }
    }

    public function delVideo($params, $type)
    {
        $send = [
            'state' => false
        ];

        do {
            $idArr = explode(',', $params['ids']);
            $idArr = collect($idArr)->filter()->unique()->values()->all();
            if (!$idArr) {
                $send['error'] = self::$errors['ids_error'];
                break;
            }

            switch ($type) {
                case 1:
                    $rows = UploadLog::query()->whereIn('id', $idArr)->where('admin_id', $params['admin_id'])->where('is_deleted', 0)->get();
                    $model = new UploadLog();
                    $table = 'upload_logs';
                    break;
                case 2:
                    $rows = ClipLog::query()->whereIn('id', $idArr)->where('admin_id', $params['admin_id'])->where('is_deleted', 0)->get();
                    $model = new ClipLog();
                    $table = 'clip_logs';
                    break;
                default:
                    $rows = CombineLog::query()->whereIn('id', $idArr)->where('admin_id', $params['admin_id'])->where('is_deleted', 0)->get();
                    $model = new CombineLog();
                    $table = 'combine_logs';
            }

            $idUpdate = $rows->pluck('id')->all();
            if (!$idUpdate) {
                $send['error'] = self::$errors['row_not_exist'];
                break;
            }

            $model->getConnection()->beginTransaction();
            try {
                $update = $model->query()->whereIn('id', $idUpdate)->update(['is_deleted' => 1]);
                if (!$update) {
                    throw new \Exception(self::$errors['del_error']);
                }

                $model->getConnection()->commit();
                foreach ($rows as $row) {
                    $this->syncSearch($table, $row, 'del');
                }
                $send = [
                    'state' => true,
                    'data' => []
                ];
            } catch (\Exception $e) {
                $model->getConnection()->rollBack();
                $send['error'] = $e->getMessage();
            }
        } while (0);

        return $send;
    }

    public function updateVideo($params, $type)
    {
        $send = [
            'state' => false
        ];

        switch ($type) {
            case 1:
                $model = new UploadLog();
                break;
            case 2:
                $model = new ClipLog();
                break;
            default:
                $model = new CombineLog();
        }

        do {
            $row = $model->query()->where('id', $params['id'])->first();
            if (!$row) {
                $send['error'] = self::$errors['row_not_exist'];
                break;
            }
            if ($row->admin_id != $params['admin_id']) {
                $send['error'] = self::$errors['no_right'];
                break;
            }

            $row->getConnection()->beginTransaction();
            try {
                foreach (self::$fields as $v) {
                    if (isset($params[$v])) {
                        if ($v == 'is_sharing') {
                            $row->$v = (int)$params[$v];
                        } else {
                            $row->$v = $params[$v];
                        }
                    }
                }

                $update = $row->save();
                if (!$update) {
                    throw new \Exception(self::$errors['save_error']);
                }

                $row->getConnection()->commit();
                $send = [
                    'state' => true,
                    'data' => $row
                ];

                switch ($type) {
                    case 1:
                        $table = 'upload_logs';
                        break;
                    case 2:
                        $table = 'clip_logs';
                        break;
                    default:
                        $table = 'combine_logs';
                }
                $this->syncSearch($table, $row, 'update');
            } catch (\Exception $e) {
                $row->getConnection()->rollBack();
                $send['error'] = $e->getMessage();
            }
        } while (0);

        return $send;
    }
}
