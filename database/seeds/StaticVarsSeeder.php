<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StaticVarsSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('static_vars')->insert([
            'name' => 'dean_name',
            'value' => 'Мукажанов Н.К.',
        ]);

        DB::table('static_vars')->insert([
            'name' => 'executor_name',
            'value' => 'Самат А.',
        ]);

        DB::table('static_vars')->insert([
            'name' => 'phone_number',
            'value' => '330-85-67 (внут.2063)',
        ]);

        DB::table('static_vars')->insert([
            'name' => 'vice_rector_of_aivd_name',
            'value' => 'Умаров Тимур Фаридович',
        ]);
    }
}
