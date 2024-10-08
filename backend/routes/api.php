<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\UserPreferenceController;
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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user/preferences', [UserPreferenceController::class, 'getPreferences']);
    Route::post('/user/preferences', [UserPreferenceController::class, 'setPreferences']);
    Route::get('/user/news-feed', [ArticleController::class, 'personalizedFeed']);

});

Route::get('/articles', [ArticleController::class, 'search']);
Route::get('/sources',[ArticleController::class, 'getSources']);
Route::get('/categories',[ArticleController::class, 'getCategories']);
Route::get('/authors',[ArticleController::class, 'getAuthors']);
