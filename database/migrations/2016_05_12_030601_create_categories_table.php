<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
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
        Schema::create('categories', function(Blueprint $table) {
            $table->increments('id');
            //栏目名称
            $table->string('title');
            $table->integer('parent_id')->unsigned()->default(0)->comment('上级栏目');
            $table->string('list_template')->comment('列表模板');
            $table->string('detail_template')->comment('内容模板');
            $table->integer('rank')->unsigned()->default(0)->comment('排序');            
            $table->timestamps();
            
            $table->index(['title']);
            $table->index(['rank']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('categories');
    }

}
