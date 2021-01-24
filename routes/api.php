<?php

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

Route::middleware(['auth:api'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', 'Api\AuthController@login');

Route::group(['middleware' => ['auth:api', 'role_auth']], function (){
    Route::apiResource('users', 'Api\UserController');
});

Route::get('generatePDF/{user_id}', [\App\Http\Controllers\Api\UserController::class, 'generatePDF']);

Route::get('reports/{file_name}', [\App\Http\Controllers\Api\UserController::class, 'getReportPDF']);
