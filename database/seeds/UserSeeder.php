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
            'username' => 'admin',
            'password' => Hash::make('adminadmin'),
            'role_id' => 1
        ]);

        DB::table('users')->insert([
            'name' => "student",
            'username' => 'student',
            'password' => Hash::make('studentstudent'),
            'role_id' => 2
        ]);

        DB::table('users')->insert([
            'name' => "dean",
            'username' => 'dean',
            'password' => Hash::make('deandean'),
            'role_id' => 3
        ]);
    }
}
