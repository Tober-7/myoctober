<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Tobiasfasanok\Users\Models\User;

Route::prefix('api/v1')->group(function () {
    Route::get('/users', function () {
        return User::all();
    });

    Route::post('/users', function (Request $request) {
        $email = $request->email;
        $pass = $request->password;
        $conPass = $request->confirmationPassword;

        foreach (User::all() as $user) if ($user->email === $email) return ["data" => "User with this e-mail address ({$email}) already exists.", "status" => 500];

        if (strlen($pass) < 8) return ["data" => "Password must be at least 8 characters long.", "status" => 500];
        if ($pass !== $conPass) return ["data" => "Password must match the confirmation password.", "status" => 500];
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $token = generateJWT($user->id);

        $user->token = $token;
        $user->save();

        return ["data" => $token, "status" => 200];
    });

    Route::put('/users/{user}', function (int $id, Request $request) {
        $user = User::find($id);

        if (!$user) return ["data" => "User with this id ({$id}) does not exist.", "status" => 500];

        $oldPass = $request->oldPassword;
        $newPass = $request->newPassword;
        $conPass = $request->confirmationPassword;

        if ($oldPass !== $user->password) return ["data" => "The password you entered does not match your old password.", "status" => 500];
        if (strlen($newPass) < 8) return ["data" => "New password must be at least 8 characters long.", "status" => 500];
        if ($newPass !== $conPass) return ["data" => "New password must match the confirmation password.", "status" => 500];
        if ($oldPass === $newPass) return ["data" => "New password cannot match the old password.", "status" => 500];
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->newPassword;
        $user->save();

        return ["data" => "User updated successfully!", "status" => 200];
    });

    Route::delete('/users/{user}', function (int $id) {
        $user = User::find($id);

        if (!$user) return ["data" => "User with this id ({$id}) does not exist.", "status" => 500];

        // echo json_encode(["data" => "User deleted successfully!", "status" => 200]);
        User::destroy($id);
    });

    Route::get('/users/login', function (Request $request) {
        foreach (User::all() as $user) {
            if ($user->email === $request->email) {
                if ($user->password === $request->password) {
                    if ($user->token) {
                        return ["data" => $user->token, "status" => 200];
                    } else {
                        $token = generateJWT($user->id);
    
                        $user->token = $token;
                        $user->save();
    
                        return ["data" => $token, "status" => 200];
                    }
                }
                else return ["data" => "Wrong password.", "status" => 500];
            }
        }
        return ["data" => "User with this e-mail does not exist.", "status" => 500];
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

function decodeJWT($token) {
    [$header, $payload, $signatureg] = explode('.', $token);
    return json_decode(base64_decode($payload))->user_id;
}

// use \October\Rain\Database\Model;

// $model = new Model();
// $model->bindEvent('model.afterCreate', function () {
//     \Log::info("{$model->name} was created!");
// });