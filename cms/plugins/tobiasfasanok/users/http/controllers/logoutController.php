<?php namespace Tobiasfasanok\Users\Http\Controllers;

use Illuminate\Http\Request;

use Tobiasfasanok\Users\Models\User;

use Tobiasfasanok\Users\Classes\Auth;

class LogoutController {
    public static function call(Request $request) {
        $token = $request->bearerToken();

        $id = Auth::readJWT($token);
        $user = User::find($id);
        
        $user->update(['token' => null]);

        return response("User logged out successfully!", 200);
    }
}

?>