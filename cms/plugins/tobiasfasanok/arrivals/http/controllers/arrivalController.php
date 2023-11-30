<?php namespace Tobiasfasanok\Arrivals\Http\Controllers;

use Illuminate\Http\Request;

use Tobiasfasanok\Users\Models\User;
use Tobiasfasanok\Arrivals\Models\Arrival;

use Tobiasfasanok\Users\Classes\Auth;

class ArrivalController {
    public function get(Request $request) {
        $token = $request->bearerToken();

        $user_id = Auth::readJWT($token);

        $arrivals = Arrival::where('user_id', $user_id)->get();

        return response($arrivals, 200);
    }

    public function create(Request $request) {
        $token = $request->bearerToken();

        $user_id = Auth::readJWT($token);
        $user = User::find($user_id);

        return response(Arrival::create(['user_id' => $user_id, 'user_name' => $user->name, 'date' => $request->date]), 200);
    }

    public function delete(int $id, Request $request) {
        $token = $request->bearerToken();

        $arrival = Arrival::find($id);

        $arrival->delete();

        return response("Arrival entry deleted successfully!", 200);
    }
}

?>