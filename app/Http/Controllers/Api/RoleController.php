<?php

namespace App\Http\Controllers\Api;

use Amir\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return response($roles);
    }
}
