<?php

use Amir\Permission\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::firstOrCreate(['name' => 'ADMIN']);
        Role::firstOrCreate(['name' => 'STUDENT']);
        Role::firstOrCreate(['name' => 'DEAN']);
        Role::firstOrCreate(['name' => 'DEPUTY_DEAN']);
        Role::firstOrCreate(['name' => 'TEACHER']);
    }
}
