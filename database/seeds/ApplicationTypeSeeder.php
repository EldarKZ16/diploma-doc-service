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
            'signer_orders' => json_encode([3, 2]),
            'description' => 'Справка по месту требования'
        ]);
    }
}
