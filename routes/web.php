<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Auth\OAuthController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [PostController::class, 'index'])
    ->name('root');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

//認証あり
Route::resource('posts', PostController::class)
    ->only(['create','store', 'edit','update','destroy'])
    ->middleware('auth');
require __DIR__.'/auth.php';

//認証なしで見られる
Route::resource('posts', PostController::class)
    ->only(['index','show']);


Route::resource('posts.comments', CommentController::class)
    ->only(['create', 'store', 'edit', 'update', 'destroy'])
    ->middleware('auth');

    require __DIR__.'/auth.php';

// authから始まるルーティングに認証前にアクセスがあった場合
Route::prefix('auth')->middleware('guest')->group(function () {
    // auth/githubにアクセスがあった場合はOAuthControllerのredirectToProviderアクションへルーティング
    Route::get('/github', [OAuthController::class, 'redirectToProvider'])
        ->name('redirectToProvider');

    // auth/github/callbackにアクセスがあった場合はOAuthControllerのoauthCallbackアクションへルーティング
    Route::get('/github/callback', [OAuthController::class, 'oauthCallback'])
        ->name('oauthCallback');
});
