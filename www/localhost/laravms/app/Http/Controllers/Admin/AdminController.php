<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminLogin;
use Illuminate\Support\Facades\Auth;
use App\Models\UploadLog;
use App\Models\ClipLog;
use App\Models\CombineLog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Services\AdminService;

class AdminController extends Controller
{
    const UPLOAD_TOTAL_NUM = 'uploadTotalNum';
    const CLIP_TOTAL_NUM = 'clipTotalNum';
    const COMBINE_TOTAL_NUM = 'combineTotalNum';

    const UPLOAD_TOTAL_SIZE = 'uploadTotalSize';
    const CLIP_TOTAL_SIZE = 'clipTotalSize';
    const COMBINE_TOTAL_SIZE = 'combineTotalSize';

    const UPLOAD_TOTAL_TIME = 'uploadTotalTime';
    const CLIP_TOTAL_TIME = 'clipTotalTime';
    const COMBINE_TOTAL_TIME = 'combineTotalTime';

    public function getGuard()
    {
        return Auth::guard('admin');
    }

    public function login(AdminLogin $request)
    {
        $params = $request->all();
        $time = time();
        $where = [
            'email' => $params['email'],
            'password' => $params['password']
        ];
        $token = $this->getGuard()->attempt($where);
        if ($token) {
            $user = $this->getGuard()->setToken($token)->getUser();
            if ($user->is_deleted) {
                $send = [
                    'state' => false,
                    'error' => '对应账号已删除'
                ];
            } else {
                $send = [
                    'state' => true,
                    'data' => [
                        'token_type' => 'bearer',
                        'token' => $token,
                        'expires_at' => config('jwt.ttl') * 60 + $time
                    ]
                ];
            }
        } else {
            $send = [
                'state' => false,
                'error' => '登录失败'
            ];
        }

        return $this->formatReturn($send);
    }

    public function user()
    {
        $user = $this->getGuard()->user();
        if ($user->is_deleted) {
            $send = [
                'state' => false,
                'error' => '对应账号已删除'
            ];
            return $this->formatReturn($send);
        }
        if ($user->id == 1) {
            $roles = ['admin'];
        } else {
            $roles = ['editor'];
        }
        $ret = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif',
            'introduction' => '',
            'roles' => $roles

        ];
        $send = [
            'state' => true,
            'data' => $ret
        ];
        return $this->formatReturn($send);
    }

    public function logout()
    {
        $this->getGuard()->logout();
        $send = ['state'=>true];
        return $this->formatReturn($send);
    }

    public function updateCache($type, $field, $adminId, $key, $ttl = 300)
    {
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

        switch ($field) {
            case 'total':
                $value = $model->query()->where('admin_id', $adminId)->count();
                break;
            case 'time':
                $value = $model->query()->where('admin_id', $adminId)->sum('duration');
                break;
            default:
                $value = $model->query()->where('admin_id', $adminId)->sum('size');
        }

        Cache::put($key, $value, $ttl);
        return $value;
    }

    public function getStat()
    {
        $adminId = $this->getGuard()->id();

        $key = self::UPLOAD_TOTAL_NUM.$adminId;
        if (Cache::has($key)) {
            $uploadNum = Cache::get($key);
        } else {
            $uploadNum = $this->updateCache(1, 'total', $adminId, $key);
        }

        $key = self::CLIP_TOTAL_NUM.$adminId;
        if (Cache::has($key)) {
            $clipNum = Cache::get($key);
        } else {
            $clipNum = $this->updateCache(2, 'total', $adminId, $key);
        }

        $key = self::COMBINE_TOTAL_NUM.$adminId;
        if (Cache::has($key)) {
            $combineNum = Cache::get($key);
        } else {
            $combineNum = $this->updateCache(3, 'total', $adminId, $key);
        }

        $key = self::UPLOAD_TOTAL_TIME.$adminId;
        if (Cache::has($key)) {
            $uploadTime = Cache::get($key);
        } else {
            $uploadTime = $this->updateCache(1, 'time', $adminId, $key);
        }

        $key = self::CLIP_TOTAL_TIME.$adminId;
        if (Cache::has($key)) {
            $clipTime = Cache::get($key);
        } else {
            $clipTime = $this->updateCache(2, 'time', $adminId, $key);
        }

        $key = self::COMBINE_TOTAL_TIME.$adminId;
        if (Cache::has($key)) {
            $combineTime = Cache::get($key);
        } else {
            $combineTime = $this->updateCache(3, 'time', $adminId, $key);
        }

        $key = self::UPLOAD_TOTAL_SIZE.$adminId;
        if (Cache::has($key)) {
            $uploadSize = Cache::get($key);
        } else {
            $uploadSize = $this->updateCache(1, 'size', $adminId, $key);
        }

        $key = self::CLIP_TOTAL_SIZE.$adminId;
        if (Cache::has($key)) {
            $clipSize = Cache::get($key);
        } else {
            $clipSize = $this->updateCache(2, 'size', $adminId, $key);
        }

        $key = self::COMBINE_TOTAL_SIZE.$adminId;
        if (Cache::has($key)) {
            $combineSize = Cache::get($key);
        } else {
            $combineSize = $this->updateCache(3, 'size', $adminId, $key);
        }

        $totalNum = $uploadNum + $clipNum + $combineNum;
        $totalSize = $uploadSize + $clipSize + $combineSize;
        $totalTime = $uploadTime + $clipTime + $combineTime;

        return [
            'state' => true,
            'data' => [
                'items' => [
                    [(int)$uploadNum, (int)$clipNum, (int)$combineNum, (int)$totalNum],
                    [(int)$uploadTime, (int)$clipTime, (int)$combineTime, (int)$totalTime],
                    [(int)$uploadSize, (int)$clipSize, (int)$combineSize, (int)$totalSize]
                ]
            ]
        ];
    }

    public function adminList(Request $request, AdminService $service)
    {
        $params = $request->all();
        $send = $service->adminList($params);
        return $this->formatReturn($send);
    }

    public function adminAdd(Request $request, AdminService $service)
    {
        $params = $request->all();
        $send = $service->adminAdd($params);
        return $this->formatReturn($send);
    }

    public function adminDel(Request $request, AdminService $service)
    {
        $params = $request->all();
        $send = $service->adminDel($params);
        return $this->formatReturn($send);
    }

    public function adminUpdate(Request $request, AdminService $service)
    {
        $params = $request->all();
        $send = $service->adminUpdate($params);
        return $this->formatReturn($send);
    }
}
