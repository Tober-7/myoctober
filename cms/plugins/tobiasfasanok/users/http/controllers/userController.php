<?php namespace Tobiasfasanok\Users\Http\Controllers;

use Illuminate\Routing\Controller;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Exception;
use Validator;

use Tobiasfasanok\Users\Models\User;

use Tobiasfasanok\Users\Classes\Auth;
use Tobiasfasanok\Users\Classes\Validation;

use Tobiasfasanok\Users\Http\Resources\UserResource;

class UserController extends Controller {
    public function login(Request $request) {
        $email = $request->email;
        $pass = $request->password;

        $loginValidator = Validator::make(
            [
                'email' => $email,
                'password' => $pass,
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

        $user = User::where('email', $email)->first();

        if (!$user) throw new Exception("user with this email does not exist.");

        if (!Hash::check($pass, $user->password)) throw new Exception("invalid password", 500);

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
            'password' => Hash::make($pass),
        ]);

        $newToken = Auth::generateJWT($user->id);

        $user->update(['token' => $newToken]);

        return response($newToken, 200);
    }

    public function get(Request $request) {
        $token = $request->bearerToken();
        
        $id = Auth::readJWT($token);
        $user = User::find($id);
        
        $res = new UserResource($user);

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
                'password' => "required|min:8",
                'newPassword' => "required|min:8|different:password",
                'confirmationPassword' => 'same:newPassword',
            ],
            Validation::messages
        );

        if ($updatePasswordValidator->fails()) {
            throw new Exception($updatePasswordValidator->messages()->first(), 500);
        }

        if (!Hash::check($pass, $user->password)) throw new Exception("invalid password", 500);
    
        $user->update([
            'password' => Hash::make($newPass),
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