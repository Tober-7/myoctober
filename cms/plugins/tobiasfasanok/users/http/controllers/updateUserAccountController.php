<?php namespace Tobiasfasanok\Users\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Validator;

use Tobiasfasanok\Users\Models\User;

use Tobiasfasanok\Users\Classes\Auth;
use Tobiasfasanok\Users\Classes\Validation;

class UpdateUserAccountController {
    public static function call(Request $request) {
        $token = $request->bearerToken();

        $id = Auth::readJWT($token);
        $user = User::find($id);
        
        $name = $request->name;
        $email = $request->email;

        $updateAccountValidator = Validator::make(
            [
                'name' => $name,
                'email' => $email,
            ],
            [
                'name' => 'required',
                'email' => 'required|email|unique:tobiasfasanok_users_users,email',
            ],
            Validation::messages
        );

        if ($updateAccountValidator->fails()) {
            throw new Exception($updateAccountValidator->messages()->first(), 500);
        }
    
        $user->update([
            'name' => $name,
            'email' => $email,
        ]);

        return response("User account updated successfully!", 200);
    }
}

?>