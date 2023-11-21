<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Tobiasfasanok\Users\Models\User;

Route::prefix('api/v1')->group(function () {
    Route::get('/users/{user}', function (int $id) {
        $user = User::find($id);

        return [
            "user" => [
                "name" => $user->name,
                "email" => $user->email,
            ],
            "status" => 200
        ];
    });

    Route::post('/users', function (Request $request) {
        $email = $request->email;
        $pass = $request->password;
        $conPass = $request->confirmationPassword;

        foreach (User::all() as $user) if ($user->email === $email) return ["message" => "User with this e-mail address ({$email}) already exists.", "status" => 500];

        if (strlen($pass) < 8) return ["message" => "Password must be at least 8 characters long.", "status" => 500];
        if ($pass !== $conPass) return ["message" => "Password must match the confirmation password.", "status" => 500];
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $token = generateJWT($user->id);

        $user->token = $token;
        $user->save();

        return ["token" => $token, "status" => 200];
    });

    Route::put('/users/{user}', function (int $id, Request $request) {
        $user = User::find($id);

        if (!$user) return ["message" => "User with this id ({$id}) does not exist.", "status" => 500];

        $pass = $request->password;
        $newPass = $request->newPassword;
        $conPass = $request->confirmationPassword;

        if ($pass) {
            if ($pass !== $user->password) return ["message" => "The password you entered does not match your old password.", "status" => 500];
            if (strlen($newPass) < 8) return ["message" => "New password must be at least 8 characters long.", "status" => 500];
            if ($newPass !== $conPass) return ["message" => "New password must match the confirmation password.", "status" => 500];
            if ($pass === $newPass) return ["message" => "New password cannot match the old password.", "status" => 500];

            $user->password = $request->newPassword;
        }
        
        foreach (User::all() as $user) {
            if ($user->email === $request->email) return ["message" => "User with this e-mail address ({$request->email}) already exists.", "status" => 500];

            $user->email = $request->email;
        }

        $user->name = $request->name;
    
        $user->save();

        return ["message" => "User updated successfully!", "status" => 200];
    });

    Route::delete('/users/{user}', function (int $id) {
        $user = User::find($id);

        if (!$user) return ["message" => "User with this id ({$id}) does not exist.", "status" => 500];

        return ["message" => "User deleted successfully!", "status" => User::destroy($id) * 200];
    });

    Route::get('/users', function (Request $request) {
        foreach (User::all() as $user) {
            if ($user->email === $request->email) {
                if ($user->password === $request->password) {
                    if ($user->token) {
                        return ["token" => $user->token, "status" => 200];
                    } else {
                        $token = generateJWT($user->id);
    
                        $user->token = $token;
                        $user->save();
    
                        return ["token" => $token, "status" => 200];
                    }
                }
                else return ["message" => "Wrong password.", "status" => 500];
            }
        }
        return ["message" => "User with this e-mail does not exist.", "status" => 500];
    });

    Route::patch('/users/{user}', function (int $id) {
        $user = User::find($id);

        if (!$user) return ["message" => "User with this id ({$id}) does not exist.", "status" => 500];

        $user->token = null;
        $user->save();

        return ["message" => "User logged out successfully!", "status" => 200];
    });
});

function generateJWT($id) {
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    $payload = json_encode(['user_id' => $id]);

    $base64UrlHeader = base64_encode($header);
    $base64UrlPayload = base64_encode($payload);

    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, '!2&#@%?', true);
    $base64UrlSignature = base64_encode($signature);

    return $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
}

// use \October\Rain\Database\Model;

// $model = new Model();
// $model->bindEvent('model.afterCreate', function () {
//     \Log::info("{$model->name} was created!");
// });