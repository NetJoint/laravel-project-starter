<?php

use Illuminate\Database\Seeder;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use App\Models\User;

class UserTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [                
                'name' => '管理员',
                'mobile' => '13412345678',
                'email' => 'test@qq.com',
                'password' => 'test'
            ]
        ];

        foreach ($users as $i => $user) {
            Sentinel::register($user, true);
            $u = User::where('name', '=', $user['name'])->first();
            $u->roles()->sync([1, 2]);
        }
    }

}
