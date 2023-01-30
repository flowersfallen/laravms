<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UploadLog;
use App\Models\ClipLog;
use App\Models\CombineLog;

class InitEs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:es';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Init es';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $builder = \Elasticsearch\ClientBuilder::create()->setHosts(config('search.elasticsearch.hosts'));
        $builder->setLogger(app('log'));
        $es = $builder->build();

        $index = ['upload_logs', 'clip_logs', 'combine_logs'];
        $mapping = [
            'properties' => [
                'id' => [
                    'type' => 'integer'
                ],
                'title' => [
                    'type' => 'text',
                    'analyzer'=>'index_ansj',
                    'search_analyzer' => 'query_ansj',
                ],
                'duration' => [
                    'type' => 'integer'
                ],
                'width' => [
                    'type' => 'integer'
                ],
                'height' => [
                    'type' => 'integer'
                ],
                'thumb' => [
                    'type' => 'keyword'
                ],
                'video' => [
                    'type' => 'keyword'
                ],
                'admin_id' => [
                    'type' => 'integer'
                ],
                'created_at' => [
                    'type' => 'long'
                ]
            ]
        ];

        //delete
        foreach ($index as $v) {
            $params = [
                'index' => $v
            ];
            $check = $es->indices()->exists($params);
            if ($check) {
                $es->indices()->delete($params);
            }
        }

        //create
        foreach ($index as $v) {
            if ($v == 'clip_logs') {
                $mapping['properties']['tags'] = ['type' => 'keyword'];
                $params = [
                    'index' => $v,
                    'body' => [
                        'mappings' => $mapping
                    ],
                ];
            } else {
                $params = [
                    'index' => $v,
                    'body' => [
                        'mappings' => $mapping
                    ],
                ];
            }
            $es->indices()->create($params);
        }

        //import
        foreach ($index as $v) {
            switch ($v) {
                case 'upload_logs':
                    $rows = UploadLog::query()->get();
                    foreach ($rows as $row) {
                        $record = [
                            'index' => $v,
                            'id' => $row->id,
                            'body' => [
                                'id' => $row->id,
                                'title' => $row->title,
                                'duration' => $row->duration,
                                'width' => $row->width,
                                'height' => $row->height,
                                'thumb' => $row->thumb,
                                'video' => $row->video,
                                'admin_id' => $row->admin_id,
                                'created_at' => strtotime($row->created_at)
                            ]
                        ];
                        $es->index($record);
                    }
                    break;
                case 'clip_logs':
                    $rows = ClipLog::query()->get();
                    foreach ($rows as $row) {
                        $record = [
                            'index' => $v,
                            'id' => $row->id,
                            'body' => [
                                'id' => $row->id,
                                'title' => $row->title,
                                'duration' => $row->duration,
                                'width' => $row->width,
                                'height' => $row->height,
                                'thumb' => $row->thumb,
                                'video' => $row->video,
                                'admin_id' => $row->admin_id,
                                'created_at' => strtotime($row->created_at),
                                'tags' => explode(',', $row->tags)
                            ]
                        ];
                        $es->index($record);
                    }
                    break;
                default:
                    $rows = CombineLog::query()->get();
                    foreach ($rows as $row) {
                        $record = [
                            'index' => $v,
                            'id' => $row->id,
                            'body' => [
                                'id' => $row->id,
                                'title' => $row->title,
                                'duration' => $row->duration,
                                'width' => $row->width,
                                'height' => $row->height,
                                'thumb' => $row->thumb,
                                'video' => $row->video,
                                'admin_id' => $row->admin_id,
                                'created_at' => strtotime($row->created_at)
                            ]
                        ];
                        $es->index($record);
                    }
            }
        }
        
        return 0;
    }
}
