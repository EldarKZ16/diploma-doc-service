<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => "Admin",
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'role_id' => 1
        ]);

        DB::table('users')->insert([
            'name' => "student",
            'email' => 'student@gmail.com',
            'password' => Hash::make('student'),
            'role_id' => 2
        ]);
    }
}
