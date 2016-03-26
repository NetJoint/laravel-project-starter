<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration {
    /*
      |--------------------------------------------------------------------------
      | 文档(documents)迁移表
      |--------------------------------------------------------------------------
      |      |
     */

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('documents', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->default(1)->comment('发布者');
            $table->string('title')->comment('标题');
            $table->string('link')->comment('链接');
            $table->string('hash')->unique()->comment('urlHash');
            $table->text('thumb')->comment('缩略图');
            $table->text('content')->comment('内容');
            $table->text('description')->comment('摘要');
            $table->integer('rank')->unsigned()->default(0)->comment('排序');
            $table->timestamps();

            $table->index('title');            
            $table->index('rank','updated_at');
            
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
    public function down() {
        Schema::drop('documents');
    }

}
