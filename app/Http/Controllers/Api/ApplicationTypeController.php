<?php

namespace App\Http\Controllers\Api;

use App\ApplicationType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApplicationTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $application_types = ApplicationType::all();
        return response($application_types);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $application_type = ApplicationType::findOrFail($id);
        return response($application_type);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return response(["status" => 200, "message" => "OK"]);
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
        $request->validate([
            'signer_orders' => 'required|array',
            'signer_orders.*' => 'required|numeric|distinct|exists:roles,id'
        ]);

        $application_type = ApplicationType::findOrFail($id);

        if ($request->has('signer_orders')) {
            $application_type->signer_orders = $request->get('signer_orders');
        }

        if ($request->has('description')) {
            $application_type->description = $request->get('description');
        }
        $application_type->save();
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
        $application_type = ApplicationType::findOrFail($id);
        $application_type->delete();
        return response(["status" => 202, "message" => "OK"], 202);
    }
}
