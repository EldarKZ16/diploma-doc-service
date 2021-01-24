<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\SignDocs;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SignDocsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sign_docs = SignDocs::all();
        return response($sign_docs);
    }

    public function showNeededToSignDocs(Request $request)
    {
        $user = $request->user();
        $sign_docs = SignDocs::where('signer_role_id', $user->role_id)->where('signed', null)->get();
        return response($sign_docs);
    }

    //
    public function signDocument(Request $request)
    {
        $request->validate([
            'sign_doc_id' => 'required|exists:sign_docs,id',
            'signed' => 'required|boolean'
        ]);

        $user = $request->user();
        $sign_doc = SignDocs::find($request->sign_doc_id);
        $signed = $request->signed;

        if ($user->role_id == $sign_doc->signer_role_id) {
            $sign_doc->signed = $signed;
            $sign_doc->save();

            $application = $sign_doc->application;

            if ($signed) {
                $signers = $sign_doc->application->applicationType->signer_orders;

                // Get the index of current signer
                $index = array_search($sign_doc->signer_role_id, $signers);

                // Check if next signer exists
                if($index !== false && $index < count($signers)-1)
                {
                    $next_signer = $signers[$index+1];
                    $new_sign_doc = new SignDocs([
                        "application_id" => $sign_doc->application_id,
                        "signer_role_id" => $next_signer
                    ]);
                    $new_sign_doc->save();
                } else {
                    $applicant_user_id = $application->applicant_user_id;
                    $generated_file_path = $this->generatePDFReport($applicant_user_id);
                    $application->uri = $generated_file_path;
                    $application->save();
                }

            } else {
                $application->uri = "Rejected by ".$user->role->name;
                $application->save();
            }
            return response(["status" => 200, "message" => "OK"], 200);
        } else {
            return response(["status" => 400, "message" => "Bad Request"], 400);
        }
    }

    private function generatePDFReport($user_id)
    {
        $user = User::findOrFail($user_id);
        $uuid = Str::uuid()->toString();
        $pdf_name = $uuid.'.pdf';
        $file_path = 'app/public/reports/'.$pdf_name;
        $file_url = 'http://localhost:8000/api/v1/application/'.$pdf_name;
        $data = (object) [
            "birth_date" => "01.01.00",
            "specialty_code" => "5B100200",
            "specialty_name" => "СИБ",
            "current_year" => "2021 - 2022",
            "start_date" => "01.09.2017",
            "end_date" => "30.06.2021"
        ];
        PDF::loadView('template1', ['user' => $user, 'file_url' => $file_url, 'data' => $data])->save(storage_path($file_path));
        return $file_url;
    }
}
