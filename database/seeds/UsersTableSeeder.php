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
                'email' => env('HLBIT_API_KEY'),
                'secret'=> env('HLBIT_SECRET_KEY'),
                'password' => bcrypt(env('HLBIT_SECRET_KEY')),
                'remember_token' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'email' => env('HLBIT_API_KEY_2'),
                'secret'=> env('HLBIT_SECRET_KEY_2'),
                'password' => bcrypt(env('HLBIT_SECRET_KEY_2')),
                'remember_token' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
