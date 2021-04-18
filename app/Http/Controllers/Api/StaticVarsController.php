<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\StaticVars;
use Illuminate\Http\Request;

class StaticVarsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vars = StaticVars::all();
        return response($vars);
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
            'name' => 'required',
            'value' => 'required'
        ]);

        $var = new StaticVars([
            'name' => $request->get("name"),
            'value' => $request->get("value"),
        ]);

        $var->save();
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
        $var = StaticVars::findOrFail($id);
        return response($var);
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
        $var = StaticVars::findOrFail($id);
        if ($request->has('name')) {
            $var->value = $request->get('name');
        }
        if ($request->has('value')) {
            $var->value = $request->get('value');
        }
        $var->save();
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
        $var = StaticVars::findOrFail($id);
        $var->delete();
        return response(["status" => 202, "message" => "OK"], 202);
    }

}
