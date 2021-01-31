<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $login_data = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($login_data)) {
            return response(['status' => 401, 'message' => 'Invalid Credentials'], 401);
        }

        $access_token = auth()->user()->createToken('authToken')->accessToken;

        $user = auth()->user();
        return response(["user_context" => $user, 'access_token' => $access_token]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response(["status" => 200, "message" => "Successfully logged out"], 200);
    }

    public function loginCampus(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $email = $request->get("email");
        $password = $request->get("password");


        // TODO: remove hardcodes, ok for MVP
        $campus_auth_response = Http::asForm()->post("https://campus.iitu.kz/auth/login", [
            "username" => $email,
            "password" => $password
        ]);

        $json_response = $campus_auth_response->json();

        if ($json_response['status'] == "success") {
            $campus_cookies = $campus_auth_response->cookies();
            $campus_token = $campus_cookies->getCookieByName("API-Token")->getValue();

            // Retrieve initial user data from campus
            $user_data_raw = Http::withCookies(
                ["API-Token" => $campus_token], "campus.iitu.kz"
            )->get("https://campus.iitu.kz/cleancoredb/user/byname/".$email);

            $user_data = $user_data_raw->json();

            if ($user_data["data"]["isStudent"]) {
                $user = User::where('email', $email);

                if ($user->exists()) {
                    // Update our user password according to campus user
                    $user = $user->first();
                    $user->password = Hash::make($password);
                } else {
                    $full_name = $user_data["data"]["lastname"]." ".$user_data["data"]["firstname"]." ".$user_data["data"]["middlename"];
                    $user = new User([
                        'name' => $full_name,
                        'email' => $email,
                        'password' => Hash::make($password),
                        'role_id' => 2
                    ]);
                }
                $user->save();

                try {
                    $access_token = $user->createToken('authToken')->accessToken;
                    return response(["user_context" => $user, 'access_token' => $access_token]);
                } finally {
                    $campus_user_id = $user_data["data"]["userId"];

                    $full_user_data = Http::withCookies(
                        ["API-Token" => $campus_token], "campus.iitu.kz"
                    )->get("https://campus.iitu.kz/joincoredb/user/".$campus_user_id)->json();

                    $user->campus_user_data = json_encode($full_user_data["data"], JSON_UNESCAPED_UNICODE);
                    $user->save();
                }
            } else {
                return response(["status" => 403, "message" => "Not allowed, only students"], 403);
            }
        } else {
            return response($json_response, 401);
        }
    }
}
