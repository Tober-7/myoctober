<?php namespace Tobiasfasanok\Arrivals\Http\Controllers;

use Illuminate\Http\Request;

use Tobiasfasanok\Users\Models\User;
use Tobiasfasanok\Arrivals\Models\Arrival;

use Tobiasfasanok\Users\Classes\Auth;

class CreateArrivalController {
    public static function call(Request $request) {
        $token = $request->bearerToken();

        $user_id = Auth::readJWT($token);
        $user = User::find($user_id);

        return response(Arrival::create(['user_id' => $user_id, 'user_name' => $user->name, 'date' => $request->date]), 200);
    }
}

?>