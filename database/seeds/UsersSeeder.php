<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user=\App\User::create(['first_name'=>'fire',
            'last_name'=>'solutions',
            'username'=>'fire',
            'email'=>'fire@gmail.com',
            'password'=>bcrypt('123456')
        ]);
    }
}
