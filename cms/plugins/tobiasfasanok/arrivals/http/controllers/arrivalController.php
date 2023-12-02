<?php namespace Tobiasfasanok\Arrivals\Http\Controllers;

use Illuminate\Routing\Controller;

use Illuminate\Http\Request;

use Tobiasfasanok\Arrivals\Models\Arrival;

use Tobiasfasanok\Users\Classes\Auth;

class ArrivalController extends Controller {
    public function get(Request $request) {
        $token = $request->bearerToken();

        $user_id = Auth::readJWT($token);

        $arrivals = Arrival::where('user_id', $user_id)->get();

        return response($arrivals, 200);
    }

    public function create(Request $request) {
        $token = $request->bearerToken();

        $user_id = Auth::readJWT($token);

        $arrival = Arrival::create(['user_id' => $user_id, 'date' => $request->date]);
        
        return response($arrival, 200);
    }

    public function delete(int $id, Request $request) {
        $token = $request->bearerToken();

        $arrival = Arrival::find($id);

        $arrival->delete();

        return response("Arrival entry deleted successfully!", 200);
    }
}

?>