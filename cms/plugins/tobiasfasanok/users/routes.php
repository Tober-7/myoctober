<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Tobiasfasanok\Users\Models\User;
use Tobiasfasanok\Arrivals\Models\Arrival;

Route::prefix('api/v1')->group(function () {
    Route::get('/users/{user}', function (int $id, Request $request) {
        // User check
        if (checkUser($id, $request->bearerToken()) !== 200) return response(checkUser($id, $request->bearerToken()), 500);
        // User check

        $user = User::find($id);

        $res = ["name" => $user->name, "email" => $user->email];
        return response($res, 200);
    });

    Route::post('/users', function (Request $request) {
        $email = $request->email;
        $pass = $request->password;
        $conPass = $request->confirmationPassword;

        foreach (User::all() as $user) if ($user->email === $email) return response("User with this e-mail address ({$email}) already exists.", 500);

        if (strlen($pass) < 8) return response("Password must be at least 8 characters long.", 500);
        if ($pass !== $conPass) return response("Password must match the confirmation password.", 500);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $token = generateJWT($user->id);

        $user->token = $token;
        $user->save();

        return response($token, 200);
    });

    Route::put('/users/{user}', function (int $id, Request $request) {
        // User check
        if (checkUser($id, $request->bearerToken()) !== 200) return response(checkUser($id, $request->bearerToken()), 500);
        // User check

        $user = User::find($id);
        
        $pass = $request->password;
        $newPass = $request->newPassword;
        $conPass = $request->confirmationPassword;
        
        if (!($pass && $user->email === $request->email))
        foreach (User::all() as $user_) {
            if ($user_->email === $request->email) return response("User with this e-mail address ({$request->email}) already exists.", 500);
            
            $user->email = $request->email;
        }
        
        if ($pass) {
            if ($pass !== $user->password) return response("The password you entered does not match your old password.", 500);
            if (strlen($newPass) < 8) return response("New password must be at least 8 characters long.", 500);
            if ($newPass !== $conPass) return response("New password must match the confirmation password.", 500);
            if ($pass === $newPass) return response("New password cannot match the old password.", 500);
            
            $user->password = $request->newPassword;
        }

        $user->name = $request->name;
    
        $user->save();

        return response("User updated successfully!", 200);
    });

    Route::delete('/users/{user}', function (int $id, Request $request) {
        // User check
        if (checkUser($id, $request->bearerToken()) !== 200) return response(checkUser($id, $request->bearerToken()), 500);
        // User check

        foreach (Arrival::all() as $arrival) if ($arrival->user_id === $id) Arrival::destroy($arrival->id);

        return response("User deleted successfully!", User::destroy($id) * 200);
    });

    Route::get('/users', function (Request $request) {
        foreach (User::all() as $user) {
            if ($user->email === $request->email) {
                if ($user->password === $request->password) {
                    if ($user->token) {
                        return response($user->token, 200);
                    } else {
                        $token = generateJWT($user->id);
    
                        $user->token = $token;
                        $user->save();
    
                        return response($token, 200);
                    }
                }
                else return response("Wrong password.", 500);
            }
        }
        return response("User with this e-mail does not exist.", 500);
    });

    Route::patch('/users/{user}', function (int $id, Request $request) {
        // User check
        if (checkUser($id, $request->bearerToken()) !== 200) return response(checkUser($id, $request->bearerToken()), 500);
        // User check

        $user = User::find($id);

        $user->token = null;
        $user->save();

        return response("User logged out successfully!", 200);
    });
});

function checkUser($id, $token) {
    $user = User::find($id);
        
    if (!$user) return "User with this id ({$id}) does not exist.";

    if (!$token) return "No authorization token found.";

    if (json_decode(readJWT($token))->user_id == $id) return 200;
    else return "Invalid authorization token.";
}

function generateJWT($id) {
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    $payload = json_encode(['user_id' => $id]);

    $base64UrlHeader = base64_encode($header);
    $base64UrlPayload = base64_encode($payload);

    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, '!2&#@%?', true);
    $base64UrlSignature = base64_encode($signature);

    return $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
}

function readJWT($token) {
    $payload = explode('.', $token)[1];
    return base64_decode($payload);
}