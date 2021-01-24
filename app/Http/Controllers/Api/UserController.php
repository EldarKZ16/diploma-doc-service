<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Str;

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'name' => 'required',
            'password' => 'nullable|min:8',
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = new User([
            'name' => $request->get("name"),
            'email' => $request->get("email"),
            'password' => Hash::make(request("password", "12345678")),
            'role_id' => $request->get("role_id")
        ]);

        $user->save();
        return response(["status" => 200, "message" => "OK"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response(["status" => 202, "message" => "OK"], 202);
    }

    public function generatePDF($id)
    {
        $user = User::findOrFail($id);
        $uuid = Str::uuid()->toString();
        $pdf_name = 'report.pdf';
        $file_path = 'app/public/reports/'.$pdf_name;
        view()->share('user', $user);
        $file_url = 'http://localhost:8000/api/reports/'.$pdf_name;
        $pdf = PDF::loadView('template1', ['user' => $user, 'file_url' => $file_url])->save(storage_path($file_path));
//        return $pdf->download($pdf_name);
        return response()->file(storage_path($file_path));
    }

    public function getReportPDF($file_name)
    {
        $pathToFile = storage_path('app/public/reports/'.$file_name);
        if (is_file($pathToFile)) {
            return response()->file($pathToFile);
        } else {
            return response(["status" => 404, "message" => "Not Found"], 404);
        }
    }
}
