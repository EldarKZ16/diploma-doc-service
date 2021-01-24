<?php

use Amir\Permission\Models\Permission;
use Amir\Permission\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Route;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission_ids = [];
        foreach (Route::getRoutes()->getRoutes() as $key => $route)
        {
            $action = $route->getActionname();

            $permission_check = Permission::where(
                ["action"=>$action]
            )->first();
            if(!$permission_check){
                $permission = new Permission;
                $permission->action = $action;
                $permission->save();
                $permission_ids[] = $permission->id;
            }
        }
        $admin_role = Role::where("name", "ADMIN")->first();
        $admin_role->permissions()->attach($permission_ids);
    }
}
