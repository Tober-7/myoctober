<?php namespace Tobiasfasanok\Arrivals\Http\Controllers;

use Illuminate\Http\Request;

use Tobiasfasanok\Arrivals\Models\Arrival;

use Tobiasfasanok\Users\Classes\Auth;

class GetArrivalsController {
    public static function call(Request $request) {
        $token = $request->bearerToken();

        $user_id = Auth::readJWT($token);

        $arrivals = Arrival::where('user_id', $user_id)->get();

        return response($arrivals, 200);
    }
}

?>