<?php namespace Tobiasfasanok\Arrivals\Http\Controllers;

use Illuminate\Http\Request;

use Tobiasfasanok\Arrivals\Models\Arrival;

class DeleteArrivalController {
    public static function call(int $id, Request $request) {
        $token = $request->bearerToken();

        $arrival = Arrival::find($id);

        $arrival->delete();

        return response("Arrival entry deleted successfully!", 200);
    }
}

?>