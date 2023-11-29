<?php namespace Tobiasfasanok\Users\Http\Controllers;

use Illuminate\Http\Request;

use Tobiasfasanok\Users\Models\User;

use Tobiasfasanok\Users\Classes\Auth;

class GetUserController {
    public static function call(Request $request) {
        $token = $request->bearerToken();
        
        $id = Auth::readJWT($token);
        $user = User::find($id);
        
        $res = ["name" => $user->name, "email" => $user->email];

        return response($res, 200);
    }
}

?>