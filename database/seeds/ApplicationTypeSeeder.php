<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('application_types')->insert([
            'name' => 'template1',
            'signer_orders' => json_encode([1]),
            'description' => 'Справка по месту требования'
        ]);

        DB::table('application_types')->insert([
            'name' => 'template2',
            'signer_orders' => json_encode([1]),
            'description' => 'Приложение 2-1'
        ]);

        DB::table('application_types')->insert([
            'name' => 'template3',
            'signer_orders' => json_encode([1]),
            'description' => 'Приложение 4'
        ]);

        DB::table('application_types')->insert([
            'name' => 'template4',
            'signer_orders' => json_encode([1]),
            'description' => 'Приложение 6'
        ]);

        DB::table('application_types')->insert([
            'name' => 'template5',
            'signer_orders' => json_encode([1]),
            'description' => 'Приложение 8'
        ]);

        DB::table('application_types')->insert([
            'name' => 'template6',
            'signer_orders' => json_encode([1]),
            'description' => 'Приложение 9'
        ]);
    }
}
