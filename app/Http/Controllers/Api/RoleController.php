<?php

namespace App\Http\Controllers\Api;

use Amir\Permission\Models\Role;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return response($roles);
    }
}
