<?php namespace Tobiasfasanok\Users\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Validator;

use Tobiasfasanok\Users\Models\User;

use Tobiasfasanok\Users\Classes\Auth;
use Tobiasfasanok\Users\Classes\Validation;

class UserController {
    public function login(Request $request) {
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

    public function create(Request $request) {
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
                'email' => 'required|email|unique:tobiasfasanok_users,email',
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

    public function get(Request $request) {
        $token = $request->bearerToken();
        
        $id = Auth::readJWT($token);
        $user = User::find($id);
        
        $res = ["name" => $user->name, "email" => $user->email];

        return response($res, 200);
    }

    public function logout(Request $request) {
        $token = $request->bearerToken();

        $id = Auth::readJWT($token);
        $user = User::find($id);
        
        $user->update(['token' => null]);

        return response("User logged out successfully!", 200);
    }

    public function updateAccount(Request $request) {
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
                'email' => 'required|email|unique:tobiasfasanok_users,email',
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

    public function updatePassword(Request $request) {
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
                'password' => "required|min:8|exists:tobiasfasanok_users,password,id,{$id}",
                'newPassword' => "required|min:8|unique:tobiasfasanok_users,password,NULL,id,id,{$id}",
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

    public function delete(Request $request) {
        $token = $request->bearerToken();

        $id = Auth::readJWT($token);
        $user = User::find($id);

        $user->arrivals()->where('user_id', $id)->delete();
        $user->delete();

        return response("User deleted successfully!", 200);
    }
}

?>