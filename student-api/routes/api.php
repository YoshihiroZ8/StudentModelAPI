<?php

use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [UserController::class, 'userLogin']);
Route::post('register', [UserController::class, 'register']);

Route::get('students/search', [StudentController::class, 'search']);

Route::post('importfile', [StudentController::class, 'import']);
Route::get('exportfile', [StudentController::class, 'export']);
// Route::middleware(['auth:api'])->get('/students', [StudentController::class, 'index']);
// Route::group(['middleware' => 'auth:api'], function(){
// // Route::get('students', [StudentController::class, 'index']);
// Route::get('students', [StudentController::class, 'index']);
// });


Route::get('students', [StudentController::class, 'index']);
Route::post('students', [StudentController::class, 'store']);
Route::get('students/{id}', [StudentController::class, 'show']);
Route::get('students/{id}/edit', [StudentController::class, 'edit']);
Route::put('students/{id}/edit', [StudentController::class, 'update']);
Route::delete('students/{id}/delete', [StudentController::class, 'destroy']);
