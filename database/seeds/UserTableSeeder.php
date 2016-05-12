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
                'email' => 'admin@qq.com',
                'password' => '1234'
            ],
            [                
                'name' => '普通用户',
                'mobile' => '13412345679',
                'email' => 'user@qq.com',
                'password' => '1234'
            ]
        ];

        foreach ($users as $i => $user) {
            Sentinel::register($user, true);
            $u = User::where('name', '=', $user['name'])->first();
            if($i == 0){
                $u->roles()->sync([1,2]);
            }else{
                $u->roles()->sync([2]);
            }
            
        }
    }

}
