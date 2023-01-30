<?php

namespace App\Library\Parser;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class BilibiliParser implements ParserInterface
{
    public $referer = 'https://m.bilibili.com';
    public $agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36";
    public $url = '';
    public $aid = '';
    public $cid = '';
    public $data = [];
    
    public function __construct($url)
    {
        $this->url = $url;
    }

    public function getVideo()
    {
        $this->getAidAndCid();
        $this->getVideoTitle();
        $this->getVideoUrl();
        return $this->data;
    }

    public function getAidAndCid()
    {
        $headers = [
            'User-Agent' => $this->agent
        ];
        $client = new Client([
            'headers' => $headers
        ]);
        try {
            $res = $client->get($this->url);
            $ret = $res->getBody()->getContents();
            preg_match('/"aid":([0-9]+),/i', $ret, $aid);
            preg_match('/"cid":([0-9]+),/i', $ret, $cid);
            if ($aid && isset($aid[1])) {
                $this->aid = $aid[1];
            }
            if ($cid && isset($cid[1])) {
                $this->cid = $cid[1];
            }
        } catch (ClientException $e) {
            return;
        }
    }

    public function getVideoTitle()
    {
        if (empty($this->aid)) {
            return;
        }

        $apiUrl = 'https://api.bilibili.com/x/web-interface/view?aid=' . $this->aid;
        $headers = [
            'Referer' => $this->referer
        ];
        $client = new Client([
            'headers' => $headers
        ]);

        try {
            $res = $client->get($apiUrl);
            $ret = $res->getBody()->getContents();
            $result = json_decode($ret, true);
            if (json_last_error() === JSON_ERROR_NONE && !empty($result) && isset($result['code']) && $result['code'] == 0) {
                $data = $result['data'];
                $title = isset($data['title']) ? $data['title'] : '';
                $pic = isset($data['pic']) ? $data['pic'] : '';
                $tag = isset($data['tname']) ? $data['tname'] : '';
                $mid = isset($data['owner']['mid']) ? $data['owner']['mid'] : '';
                $name = isset($data['owner']['name']) ? $data['owner']['name'] : '';
                $face = isset($data['owner']['face']) ? $data['owner']['face'] : '';
                $this->data['videoTitle'] = $title;
                $this->data['videoPic'] = $pic;
                $this->data['videoTag'] = $tag;
                $this->data['userId'] = $mid;
                $this->data['userName'] = $name;
                $this->data['userThumb'] = $face;
            }

        } catch (ClientException $e) {
            return;
        }
    }

    public function getVideoUrl()
    {
        if (empty($this->aid) || empty($this->cid)) {
            return;
        }

        $apiUrl = 'https://api.bilibili.com/x/player/playurl';
        $query = [
            'avid'  => $this->aid,
            'cid'   => $this->cid,
            'qn'    => 80,
            'otype' => 'json',
            'type'  => 'mp4',
            'platform' => 'html5',
            'fnver' => 0,
            'fnval' => 1
        ];
        $apiUrl .= '?'.http_build_query($query);
        $headers = [
            'Referer' => $this->referer,
            'origin'    => $this->referer,
            'Host'    => 'api.bilibili.com',
            'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_5_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148 MicroMessenger/7.0.14(0x17000e29) NetType/WIFI Language/zh_CN',
        ];
        $client = new Client([
            'headers' => $headers
        ]);

        try {
            $res = $client->get($apiUrl);
            $ret = $res->getBody()->getContents();
            $result = json_decode($ret, true);
            if (json_last_error() === JSON_ERROR_NONE && !empty($result) && isset($result['code']) && $result['code'] == 0) {
                $data = $result['data'];
                $url = isset($data['durl'][0]['url']) ? $data['durl'][0]['url'] : '';
                $this->data['videoUrl'] = $url;
            }
        } catch (ClientException $e) {
            return;
        }
    }
}