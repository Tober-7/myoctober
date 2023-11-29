<?php namespace Tobiasfasanok\Users\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Validator;

use Tobiasfasanok\Users\Models\User;

use Tobiasfasanok\Users\Classes\Auth;
use Tobiasfasanok\Users\Classes\Validation;

class UpdateUserPasswordController {
    public static function call(Request $request) {
        $token = $request->bearerToken();

        $id = Auth::readJWT($token);
        $user = User::find($id);

        $pass = $request->password;
        $newPass = $request->newPassword;
        $conPass = $request->confirmationPassword;

        $updatePasswordValidator = Validator::make(
            [
                'password' => $pass,
                'newPassword' => $conPass,
                'confirmationPassword' => $conPass,
            ],
            [
                'password' => "required|min:8|exists:tobiasfasanok_users_users,password,id,{$id}",
                'newPassword' => "required|min:8|unique:tobiasfasanok_users_users,password,NULL,id,id,{$id}",
                'confirmationPassword' => 'same:newPassword',
            ],
            Validation::messages
        );

        if ($updatePasswordValidator->fails()) {
            throw new Exception($updatePasswordValidator->messages()->first(), 500);
        }
    
        $user->update([
            'password' => $newPass,
        ]);

        return response("User password updated successfully!", 200);
    }
}

?>