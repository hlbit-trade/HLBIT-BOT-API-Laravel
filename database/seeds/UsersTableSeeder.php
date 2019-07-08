<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        DB::table('users')->insert([
            [
                'id' => 1,
                'email' => 'HLBIT-API-O3SQ-LB17-FGL8-ZO9H',
                'secret'=> 'gjc07ug4qmy6hxqm4rktf78gqgiy9kiyniw4yyzjd1110w967c4xecs89rwu4wjc',
                'password' => bcrypt('gjc07ug4qmy6hxqm4rktf78gqgiy9kiyniw4yyzjd1110w967c4xecs89rwu4wjc'),
                'remember_token' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
