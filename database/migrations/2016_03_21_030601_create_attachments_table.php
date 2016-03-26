<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachmentsTable extends Migration
{
    /*
      |--------------------------------------------------------------------------
      | 栏目(categories)迁移表
      |--------------------------------------------------------------------------
      |
      | 栏目(categories)
      |
     */

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('上传者');
            $table->string('title')->comment('文件名');
            $table->string('original')->comment('原文件名');
            $table->string('url')->comment('访问地址');
            $table->string('mimetype')->comment('文件mimetype');
            $table->string('type')->comment('文件类型');
            $table->bigInteger('size')->unsigned()->comment('字节数');                        
            $table->timestamps();
            
            $table->foreign('user_id')
                    ->references('id')->on('users')
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
        Schema::drop('attachments');
    }

}
