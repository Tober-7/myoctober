<?php namespace Tobiasfasanok\Users\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Validator;

use Tobiasfasanok\Users\Models\User;

use Tobiasfasanok\Users\Classes\Auth;
use Tobiasfasanok\Users\Classes\Validation;

class CreateUserController {
    public static function call(Request $request) {
        $name = $request->name;
        $email = $request->email;
        $pass = $request->password;
        $conPass = $request->confirmationPassword;

        $createValidator = Validator::make(
            [
                'name' => $name,
                'email' => $email,
                'password' => $pass,
                'confirmationPassword' => $conPass,
            ],
            [
                'name' => 'required',
                'email' => 'required|email|unique:tobiasfasanok_users_users,email',
                'password' => 'required|min:8',
                'confirmationPassword' => 'same:password',
            ],
            Validation::messages
        );

        if ($createValidator->fails()) {
            throw new Exception($createValidator->messages()->first(), 500);
        }
        
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $pass,
        ]);

        $newToken = Auth::generateJWT($user->id);

        $user->update(['token' => $newToken]);

        return response($newToken, 200);
    }
}

?>