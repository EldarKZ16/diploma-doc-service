<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([StaticVarsSeeder::class, RoleSeeder::class, PermissionTableSeeder::class, UserSeeder::class, ApplicationTypeSeeder::class]);
    }
}
