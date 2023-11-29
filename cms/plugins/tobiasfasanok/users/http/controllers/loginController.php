<?php namespace Tobiasfasanok\Users\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Validator;

use Tobiasfasanok\Users\Models\User;

use Tobiasfasanok\Users\Classes\Auth;
use Tobiasfasanok\Users\Classes\Validation;

class LoginController {
    public static function call(Request $request) {
        $email = $request->email;
        $password = $request->password;

        $loginValidator = Validator::make(
            [
                'email' => $email,
                'password' => $password,
            ],
            [
                'email' => 'required|email',
                'password' => 'required|min:8',
            ],
            Validation::messages
        );

        if ($loginValidator->fails()) {
            throw new Exception($loginValidator->messages()->first(), 500);
        }

        $user = User::where('email', $email)
        ->where('password', $password)
        ->first();

        if (!$user) {
            throw new Exception("invalid email or password.", 500);
        }

        if ($user->token) {
            return response($user->token, 200);
        }

        $newToken = Auth::generateJWT($user->id);

        $user->update(['token' => $newToken]);

        return response($newToken, 200);
    }
}

?>