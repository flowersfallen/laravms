<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('')->comment('管理员名称');
            $table->string('email')->unique()->default('')->comment('管理员邮箱');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->default('')->comment('管理员密码');
            $table->rememberToken();
            $table->timestamps();
            $table->unsignedInteger('is_deleted')->default(0)->comment('是否已删除,1是0否');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
