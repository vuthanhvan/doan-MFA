<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('signup', [AuthController::class, 'sign_up']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('user', function (Request $request) {
        $user = $request->user();
        $token = $user->createToken('apiToken')->plainTextToken;

        $res = [
            'user' => $user,
            'token' => $token
        ];

        return response()->json($res, 201);
    });
    Route::post('tokens/create', function (Request $request) {
        $token = $request->user()->createToken($request->token_name);
        return ['token' => $token->plainTextToken];
    });
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::get('nextcloud/auth/callback', function () {
    $user = Socialite::driver('nextcloud')->stateless()->user();

    // Check if the user already exists in the database.
    if (User::where('email', $user->email)->exists()) {
        $user = User::where('email', $user->email)->first();
        $token = $user->createToken('apiToken')->plainTextToken;

        // Return a JSON response with the user information.
        return redirect(env("NEXTCLOUD_REDIRECT") . "?token=" . $token);
    } else {
        // The user does not exist, so create them.
        $user = User::create([
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
        ]);
        $token = $user->createToken('apiToken')->plainTextToken;

        // Return a JSON response with the user information.
        return redirect(env("NEXTCLOUD_REDIRECT") . "?token=" . $token);
    }
});
