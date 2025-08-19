<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => 'テスト1',
            'email' => 'test1@test.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ];
        DB::table('users')->insert($param);
        $param = [
            'name' => 'テスト2',
            'email' => 'test2@test.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ];
        DB::table('users')->insert($param);
        $param = [
            'name' => 'テスト3',
            'email' => 'test3@test.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ];
        DB::table('users')->insert($param);
    }
}
