<?php

use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\SignDocsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/v1/student-login', 'Api\AuthController@loginCampus');

Route::post('/v1/login', 'Api\AuthController@login');
Route::post('/v1/logout', 'Api\AuthController@logout')->middleware(['auth:api']);
Route::middleware(['auth:api'])->get('/v1/user-context', function (Request $request) {
    return $request->user();
});

Route::get('/v1/roles', 'Api\RoleController@index');

Route::group(['middleware' => ['auth:api', 'role_auth'], 'role' => 'ADMIN'], function (){
    Route::apiResource('v1/users', 'Api\UserController');
});

Route::group(['middleware' => ['auth:api']], function (){
    Route::apiResource('v1/application-types', 'Api\ApplicationTypeController');
    Route::get('v1/application/{id}', [ApplicationController::class, 'show']);
});

Route::group(['middleware' => ['auth:api'], 'role' => 'STUDENT'], function (){
    Route::post('v1/application/submit-request', [ApplicationController::class, 'sendRequestForReport']);
    Route::get('v1/applications', [ApplicationController::class, 'showApplications']);
});

Route::group(['middleware' => ['auth:api', 'role_auth'], 'role' => 'DEAN'], function (){
    Route::get('v1/sign-docs', [SignDocsController::class, 'showNeededToSignDocs']);
    Route::post('v1/sign-docs/sign', [SignDocsController::class, 'signDocument']);
});

Route::get('v1/application/{file_name}', [ApplicationController::class, 'getPDFReport']);


