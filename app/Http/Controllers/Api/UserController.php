<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return response($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'name' => 'required',
            'password' => 'nullable|min:8',
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = new User([
            'name' => $request->get("name"),
            'username' => $request->get("username"),
            'password' => Hash::make(request("password", "12345678")),
            'role_id' => $request->get("role_id")
        ]);

        $user->save();
        return response(["status" => 200, "message" => "OK"]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($request->has('name')) {
            $user->name = $request->get('name');
        }

        if ($request->has('password')) {
            $user->password = Hash::make($request->get('password'));
        }

        if ($request->has('role_id')) {
            $user->role_id = $request->get('role_id');
        }
        $user->save();
        return response(["status" => 201, "message" => "OK"], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response(["status" => 202, "message" => "OK"], 202);
    }

    public function showCampusInfo($id)
    {
        if (auth()->user()->role->name == "STUDENT" && auth()->user()->id != $id) {
            return response(["status" => 403, "message" => "Forbidden"], 403);
        }

        $user = User::findOrFail($id);
        $user_data = json_decode($user->campus_user_data, true);
        return response($user_data);
    }

    public function getContext(Request $request)
    {
        $user = User::with('role')->findOrFail($request->user()->id);
        return response($user);
    }

}
