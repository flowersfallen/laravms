<?php

use Illuminate\Support\Facades\Route;

// 后台路由
Route::prefix('admin')->group(function () {
    Route::any('login', 'AdminController@login');

    Route::middleware(['auth:admin'])->group(function () {
        Route::any('user', 'AdminController@user');
        Route::any('logout', 'AdminController@logout');
        Route::any('getStat', 'AdminController@getStat');
        // Route::any('es', 'AdminController@es');

        //用户管理
        Route::middleware(['admin.role'])->group(function () {
            Route::get('list', 'AdminController@adminList');
            Route::post('add', 'AdminController@adminAdd');
            Route::post('del', 'AdminController@adminDel');
            Route::post('update', 'AdminController@adminUpdate');
        });

        Route::prefix('video')->group(function () {
            Route::any('/listVideo', 'VideoController@listVideo');
            Route::any('/listClip', 'VideoController@listClip');
            Route::any('/listCombine', 'VideoController@listCombine');
            Route::any('/clipVideo', 'VideoController@clipVideo');
            Route::any('/combineClip', 'VideoController@combineClip');
        });
    });

    Route::any('video/uploadVideo', 'VideoController@uploadVideo');
});
