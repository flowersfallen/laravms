<?php

namespace App\Services;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminService extends BaseService
{
    protected static $errors = [
        'email_used' => '邮箱已被使用',
        'save_error' => '保存失败',
        'del_error' => '删除失败',
        'ids_error' => '账号ids有误',
        'row_not_exist' => '未查到对应记录'
    ];

    protected static $fields = [
        'name',
        'email',
        'password'
    ];

    public function adminList($params)
    {
        $keyword = isset($params['keyword']) ? $params['keyword'] : false;
        $pagesize = isset($params['pagesize']) ? $params['pagesize'] : 15;

        $rows = Admin::query()
            ->select('id', 'name', 'email', 'created_at')
            ->when($keyword, function ($query) use ($keyword) {
                return $query->where(function ($query) use ($keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%')->orWhere('email', 'like', '%' . $keyword . '%');
                });
            })
            ->orderBy('id', 'desc')
            ->paginate($pagesize);

        $send = [
            'state' => true,
            'data' => [
                'total' => $rows->total(),
                'data' => $rows->items()
            ]
        ];
        return $send;
    }

    public function adminAdd($params)
    {
        $send = [
            'state' => false
        ];

        do {
            $row = Admin::query()->where([
                ['email', '=', $params['email']]
            ])->first();
            if ($row) {
                $send['error'] = self::$errors['email_used'];
                break;
            }

            $row = new Admin();
            $row->getConnection()->beginTransaction();
            try {
                foreach (self::$fields as $v) {
                    if (isset($params[$v])) {
                        if ($v != 'password') {
                            $row->$v = $params[$v];
                        } else {
                            $row->$v = Hash::make($params[$v]);
                        }
                    }
                }
                $insert = $row->save();
                if (!$insert) {
                    throw new \Exception(self::$errors['save_error']);
                }

                $row->getConnection()->commit();
                $send = [
                    'state' => true,
                    'data' => $row
                ];
            } catch (\Exception $e) {
                $row->getConnection()->rollBack();
                $send['error'] = $e->getMessage();
            }
        } while (0);

        return $send;
    }

    public function adminDel($params)
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

            $rows = Admin::whereIn('id', $idArr)->get();
            $idUpdate = $rows->pluck('id')->all();
            if (!$idUpdate) {
                $send['error'] = self::$errors['row_not_exist'];
                break;
            }

            $row = new Admin();
            $row->getConnection()->beginTransaction();
            try {
                $update = Admin::query()->whereIn('id', $idUpdate)->delete();
                if (!$update) {
                    throw new \Exception(self::$errors['del_error']);
                }

                $row->getConnection()->commit();
                $send = [
                    'state' => true,
                    'data' => []
                ];
            } catch (\Exception $e) {
                $row->getConnection()->rollBack();
                $send['error'] = $e->getMessage();
            }
        } while (0);

        return $send;
    }

    public function adminUpdate($params)
    {
        $send = [
            'state' => false
        ];

        do {
            $row = Admin::query()->where('id', $params['id'])->first();
            if (!$row) {
                $send['error'] = self::$errors['row_not_exist'];
                break;
            }

            if (isset($params['email'])) {
                $check = Admin::query()->where([
                    ['id', '<>', $params['id']],
                    ['email', '=', $params['email']]
                ])->first();
                if ($check) {
                    $send['error'] = self::$errors['email_used'];
                    break;
                }
            }

            $row->getConnection()->beginTransaction();
            try {
                foreach (self::$fields as $v) {
                    if (isset($params[$v])) {
                        if ($v != 'password') {
                            $row->$v = $params[$v];
                        } else {
                            $row->$v = Hash::make($params[$v]);
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
            } catch (\Exception $e) {
                $row->getConnection()->rollBack();
                $send['error'] = $e->getMessage();
            }
        } while (0);

        return $send;
    }
}
