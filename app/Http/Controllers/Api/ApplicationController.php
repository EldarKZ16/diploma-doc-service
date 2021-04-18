<?php

namespace App\Http\Controllers\Api;

use App\Application;
use App\ApplicationType;
use App\Http\Controllers\Controller;
use App\SignDocs;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the Application.
     * @authenticated
     * @queryParam page required The page number. default = 1
     * @queryParam per_page required The number of items per list. default = 15
     * @apiResourceCollection Illuminate\Http\Resources\Json\JsonResource
     * @apiResourceModel App\Models\ProductImage
     * @param Request $request
     */
    public function index(Request $request)
    {
        return JsonResource::collection(Application::latest()->paginate($request['per_page']));
    }

    public function show($id)
    {
        $application = Application::findOrFail($id);
        return response($application);
    }

    //
    public function showApplications(Request $request) {
        $user = $request->user();
        $applications = Application::where('applicant_user_id', $user->id)->orderBy('created_at', 'desc')->get();
        return response($applications);
    }

    public function sendRequestForReport(Request $request)
    {
        $request->validate([
            'application_type_id' => 'required|exists:application_types,id'
        ]);

        $user = $request->user();
        $application_type = ApplicationType::find($request->application_type_id);
        $application = new Application([
            "application_type_id" => $application_type->id,
            "applicant_user_id" => $user->id
        ]);
        $application->save();

        $signer_role_id = ($application->applicationType->signer_orders)[0];
        $sign_doc = new SignDocs([
           "application_id" => $application->id,
           "signer_role_id" => $signer_role_id
        ]);
        $sign_doc->save();

        return response(["status" => 200, "message" => "OK"], 200);
    }

    public function getPDFReport($file_name)
    {
        $path_to_file = storage_path('app/public/reports/'.$file_name);
        if (is_file($path_to_file)) {
            return response()->file($path_to_file);
        } else {
            return response(["status" => 404, "message" => "Not Found"], 404);
        }
    }

    public function getStatistics()
    {
        $all_applications = Application::count();
        $accepted_applications = Application::where('uri', 'like', 'http%')->count();
        $unprocessed_applications = Application::whereNull('uri')->count();
        $processed_applications = $all_applications - $unprocessed_applications;
        $rejected_applications = $processed_applications - $accepted_applications;

        return response([
            "all_applications" => $all_applications,
            "processed_applications" => $processed_applications,
            "accepted_applications" => $accepted_applications,
            "rejected_applications" => $rejected_applications
        ], 200);
    }
}
