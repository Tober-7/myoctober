<?php namespace Tobiasfasanok\Users\Http\Controllers;

use Illuminate\Http\Request;

use Tobiasfasanok\Users\Models\User;
use Tobiasfasanok\Arrivals\Models\Arrival;

use Tobiasfasanok\Users\Classes\Auth;

class DeleteUserController {
    public static function call(Request $request) {
        $token = $request->bearerToken();

        $id = Auth::readJWT($token);
        $user = User::find($id);

        Arrival::where('user_id', $id)->delete();
        $user->delete();

        return response("User deleted successfully!", 200);
    }
}

?>