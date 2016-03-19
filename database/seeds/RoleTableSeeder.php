<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class RoleTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table = DB::table('roles');
        $table->insert([
            'id' => 1,
            'slug' => 'admin',
            'name' => '管理员'
        ]);
        $table->insert([
            'id' => 2,
            'slug' => 'user',
            'name' => '普通用户'
        ]);
    }

}
