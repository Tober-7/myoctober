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

        foreach (User::all() as $user) if ($user->email === $email) return "User with this e-mail address ({$email}) already exists.";

        if (strlen($pass) < 8) return "Password must be at least 8 characters long.";
        if ($pass !== $conPass) return "Password must match the confirmation password.";
        
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);
    });

    Route::put('/users/{user}', function (int $id, Request $request) {
        $user = User::find($id);

        if (!$user) return "User with this id ({$id}) does not exist.";

        $oldPass = $request->oldPassword;
        $newPass = $request->newPassword;
        $conPass = $request->confirmationPassword;

        if ($oldPass !== $user->password) return "The password you entered does not match your old password.";
        if (strlen($newPass) < 8) return "New password must be at least 8 characters long.";
        if ($newPass !== $conPass) return "New password must match the confirmation password.";
        if ($oldPass === $newPass) return "New password cannot match the old password.";
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->newPassword;
        $user->save();

        return "User updated successfully!";
    });

    Route::delete('/users/{user}', function (int $id) {
        $user = User::find($id);

        if (!$user) return "User with this id ({$id}) does not exist.";

        echo "User deleted successfully!";
        User::destroy($id);
    });

    Route::get('/users/login', function (Request $request) {
        foreach (User::all() as $user) {
            if ($user->email === $request->email) {
                if ($user->password === $request->password) {
                    $token = generateJWT($user->id);

                    $user->token = $token;
                    $user->save();

                    return $token;
                }
                else return "Wrong password.";
            }
        }
        return "User with this e-mail does not exist.";
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