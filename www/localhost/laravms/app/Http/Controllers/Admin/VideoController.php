<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\VideoService;
use Illuminate\Http\Request;
use JWTAuth;
use Illuminate\Auth\AuthenticationException;

class VideoController extends Controller
{
    public function uploadVideo(Request $request, VideoService $service)
    {
        $params = $request->all();

        if (!isset($params['token']) || empty($params['token'])) {
            throw new AuthenticationException();
        }
        $token = str_replace('Bearer', '', $params['token']);
        $result = JWTAuth::setToken($token)->check(true);
        if (!$result) {
            throw new AuthenticationException();
        }

        $params = array_merge($params, ['admin_id' => $result['sub']]);
        $res = $service->upload($params);
        return $this->formatReturn($res);
    }

    public function listVideo(Request $request, VideoService $service)
    {
        $params = $request->all();
        $payload = $this->getPayload();
        $params = array_merge($params, $payload);
        $res = $service->listVideo($params, 1);
        return $this->formatReturn($res);
    }

    public function listClip(Request $request, VideoService $service)
    {
        $params = $request->all();
        $payload = $this->getPayload();
        $params = array_merge($params, $payload);
        $res = $service->listVideo($params, 2);
        return $this->formatReturn($res);
    }

    public function listCombine(Request $request, VideoService $service)
    {
        $params = $request->all();
        $payload = $this->getPayload();
        $params = array_merge($params, $payload);
        $res = $service->listVideo($params, 3);
        return $this->formatReturn($res);
    }

    public function clipVideo(Request $request, VideoService $service)
    {
        $params = $request->all();
        $payload = $this->getPayload();
        $params = array_merge($params, $payload);
        $res = $service->clipVideo($params);
        return $this->formatReturn($res);
    }

    public function combineClip(Request $request, VideoService $service)
    {
        $params = $request->all();
        $payload = $this->getPayload();
        $params = array_merge($params, $payload);
        $res = $service->combineClip($params);
        return $this->formatReturn($res);
    }

    public function setShare(Request $request, VideoService $service)
    {
        $params = $request->all();
        $payload = $this->getPayload();
        $params = array_merge($params, $payload);
        $type = isset($params['type']) && in_array($params['type'], [1, 2, 3]) ? $params['type'] : 1;
        $res = $service->updateVideo($params, $type);
        return $this->formatReturn($res);
    }

    public function delItem(Request $request, VideoService $service)
    {
        $params = $request->all();
        $payload = $this->getPayload();
        $params = array_merge($params, $payload);
        $type = isset($params['type']) && in_array($params['type'], [1, 2, 3]) ? $params['type'] : 1;
        $res = $service->delVideo($params, $type);
        return $this->formatReturn($res);
    }

    public function updateVideo(Request $request, VideoService $service)
    {
        $params = $request->all();
        $payload = $this->getPayload();
        $params = array_merge($params, $payload);
        $type = isset($params['type']) && in_array($params['type'], [1, 2, 3]) ? $params['type'] : 1;
        $res = $service->updateVideo($params, $type);
        return $this->formatReturn($res);
    }
}
