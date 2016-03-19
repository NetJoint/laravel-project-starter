<?php

/**
 * Part of the Sentinel package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Sentinel
 * @version    2.0.7
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2015, Cartalyst LLC
 * @link       http://cartalyst.com
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class MigrationCartalystSentinel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        # 用户信息表
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('password')->nullable();
            $table->text('permissions')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->timestamps();
            
            $table->index('name');
            $table->unique('email');
            $table->unique('mobile');
        });
        
        # 注册验证码
        Schema::create('activations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('code');
            $table->boolean('completed')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')
                    ->references('id')->on('users')
                    ->onDelete('cascade');
        });

        # 长期登录码，用于“记住我”功能
        Schema::create('persistences', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('code');
            $table->timestamps();
            
            $table->unique('code');
            $table->foreign('user_id')
                    ->references('id')->on('users')
                    ->onDelete('cascade');
        });

        # 密码重置码
        Schema::create('reminders', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('code');
            $table->boolean('completed')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')
                    ->references('id')->on('users')
                    ->onDelete('cascade');
        });
        
        # 密码重试过多保护
        Schema::create('throttle', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('type');
            $table->string('ip')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')
                    ->references('id')->on('users')
                    ->onDelete('cascade');
        });
        
        # 角色信息表
        Schema::create('roles', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->increments('id');
            $table->string('slug');
            $table->string('name');
            $table->text('permissions')->nullable();
            $table->timestamps();
            
            $table->unique('slug');
        });

        # 角色用户关系表
        Schema::create('role_users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->nullableTimestamps();
            
            $table->primary(['user_id', 'role_id']);
            $table->foreign('user_id')
                    ->references('id')->on('users')
                    ->onDelete('cascade');
            $table->foreign('role_id')
                    ->references('id')->on('roles')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('activations');
        Schema::drop('persistences');
        Schema::drop('reminders');
        Schema::drop('throttle');
        Schema::drop('role_users');
        Schema::drop('roles');
        Schema::drop('users');
    }
}
