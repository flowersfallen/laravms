<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClipLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clip_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('upload_id')->default(0)->comment('上传视频id');
            $table->unsignedInteger('in')->default(0)->comment('入点');
            $table->unsignedInteger('out')->default(0)->comment('出点');
            $table->string('title')->default('')->comment('标题');
            $table->string('tags')->default('')->comment('标签');
            $table->unsignedInteger('duration')->default(0)->comment('时长');
            $table->unsignedInteger('size')->default(0)->comment('大小');
            $table->unsignedInteger('bit_rate')->default(0)->comment('码率');
            $table->unsignedInteger('width')->default(0)->comment('宽');
            $table->unsignedInteger('height')->default(0)->comment('高');
            $table->string('video_codec')->default('')->comment('视频编码');
            $table->string('audio_codec')->default('')->comment('音频编码');
            $table->unsignedInteger('sample_rate')->default(0)->comment('音频采样率');
            $table->unsignedInteger('channels')->default(0)->comment('声道数');
            $table->string('channel_layout')->default('')->comment('声道分布');
            $table->string('thumb')->default('')->comment('缩略图');
            $table->string('video')->default('')->comment('文件路径');
            $table->unsignedInteger('admin_id')->default(0)->comment('管理员id');
            $table->unsignedInteger('is_sharing')->default(0)->comment('是否分享,1是0否');
            $table->unsignedInteger('is_deleted')->default(0)->comment('是否已删除,1是0否');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clip_logs');
    }
}
