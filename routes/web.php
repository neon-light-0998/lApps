<?php

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


Auth::routes();
Route::group(['middleware' => ['auth']], function() {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/home', 'HomeController@index')->name('home');
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

Route::post('/mobile/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (!Auth::attempt($credentials)) {
        return response()->json([
            'message' => 'Invalid email or password.'
        ], 401);
    }

    $user = Auth::user();
    $token = Str::random(60);

    if (\Schema::hasColumn('users', 'api_token')) {
        $user->api_token = hash('sha256', $token);
        $user->save();
    }

    return response()->json([
        'token' => $token,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ],
    ], 200);
});


    
    Route::get('adduser/{id?}', 'UserController@create');
    Route::post('user/store', 'UserController@store');
    Route::get('userlist', 'UserController@index');
    Route::get('deleteUser/{id}', 'UserController@destroy');
    Route::post('serverUser', 'UserController@getServerUserList');
});
