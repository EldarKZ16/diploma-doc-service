<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\SignDocs;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
        $sign_docs = SignDocs::with(['application', 'application.applicant'])->where('signer_role_id', $user->role_id)->where('signed', null)->get();
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

        if ($user->role_id == $sign_doc->signer_role_id && $sign_doc->signed == null) {

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
                    $application_template_name = $sign_doc->application->applicationType->name;

                    $applicant_user_id = $application->applicant_user_id;
                    $generated_file_result = $this->generatePDFReport($applicant_user_id, $application_template_name);

                    $application->uri = $generated_file_result["file_url"];
                    $application->save();

                    $pdf = $generated_file_result["pdf"];
                    $data["name"] = "Студент ".$application->applicant->name;
                    $data["email"] = $application->applicant->username."@edu.iitu.kz";
                    $data["title"] = $sign_doc->application->applicationType->description;
                    $data["body"] = "Ваш запрос выполнен!";

                    Mail::send('emails.template', $data, function($message)use($data, $pdf) {
                        $message->to($data["email"], $data["email"])
                            ->subject($data["title"])
                            ->attachData($pdf->output(), "report.pdf");
                    });
                }

            } else {
                $application->uri = "Rejected by ".$user->role->name;
                $application->save();

                $data["name"] = "Студент ".$application->applicant->name;
                $data["email"] = $application->applicant->username."@edu.iitu.kz";
                $data["title"] = $sign_doc->application->applicationType->description;
                $data["body"] = "Вам отказали в выдаче справки, обратитесь в деканат";

                Mail::send('emails.template', $data, function($message)use($data) {
                    $message->to($data["email"], $data["email"])
                        ->subject($data["title"]);
                });
            }
            $sign_doc->signed = $signed;
            $sign_doc->save();
            return response(["status" => 200, "message" => "OK"], 200);
        } else {
            return response(["status" => 400, "message" => "Bad Request"], 400);
        }
    }

    private function generatePDFReport($user_id, $application_template_name)
    {
        $user = User::findOrFail($user_id);
        $uuid = Str::uuid()->toString();
        $pdf_name = $uuid.'.pdf';
        $file_path = 'app/public/reports/'.$pdf_name;

        $hostname = env("SERVER_HOSTNAME", "http://localhost:8000");
        $file_url = $hostname."/api/v1/signed-application/".$pdf_name;

        $user_data = json_decode($user->campus_user_data, true);
        $str_to_birth_time = strtotime($user_data["birthdate"]);
        $birth_date = date('d.m.Y', $str_to_birth_time);

        $fall_start_date = env("FALL_START_DATE", 'August 1');
        $fall = new DateTime($fall_start_date);
        $today = new DateTime();

        $thisYear = date('Y');
        $lastYear = date('Y', strtotime('-1 year'));

        $current_year = $lastYear. " - ". $thisYear;
        if ($today >= $fall) {
            $nextYear = date('Y', strtotime('+1 year'));
            $current_year = $thisYear. " - ". $nextYear;
        }

        $course_count = $user_data["studentCard"]["educationForm"]["courseCount"];

        $str_to_start_time = strtotime($user_data["studentCard"]["universityStartDate"]);
        $start_date = date('d.m.Y', $str_to_start_time);
        $end_date = "30.06.".(date('Y', strtotime($start_date . "+".$course_count."years")));

        $full_name = $user_data["lastname"]." ".$user_data["firstname"]." ".$user_data["middlename"];
        $data = (object) [
            "full_name" => $full_name,
            "birth_date" => $birth_date,
            "specialty_code" => $user_data["studentCard"]["specialization"]["speciality"]["code"],
            "course" => $user_data["studentCard"]["course"],
            "course_count" => $course_count,
            "specialty_name" => $user_data["studentCard"]["specialization"]["speciality"]["professionnameru"],
            "current_year" => $current_year,
            "start_date" => $start_date,
            "end_date" => $end_date,
            "file_url" => $file_url
        ];
        $pdf = PDF::loadView($application_template_name, ['data' => $data])->save(storage_path($file_path));

        $result["pdf"] = $pdf;
        $result["file_url"] = $file_url;

        return $result;
    }
}
