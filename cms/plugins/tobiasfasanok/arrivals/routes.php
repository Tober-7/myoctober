<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Tobiasfasanok\Users\Models\User;
use Tobiasfasanok\Arrivals\Models\Arrival;

Route::prefix('api/v1/users/{user}')->group(function () {
    Route::get('/arrivals', function (int $user_id, Request $request) {
        // User check
        if (checkUser($user_id, $request->bearerToken()) !== 200) return response(checkUser($user_id, $request->bearerToken()), 500);
        // User check

        $arrivals = [];
        foreach (Arrival::all() as $arrival) if ($arrival->user_id === $user_id) array_push($arrivals, $arrival);

        return response($arrivals, 200);
    });

    Route::post('/arrivals', function (int $user_id, Request $request) {
        // User check
        if (checkUser($user_id, $request->bearerToken()) !== 200) return response(checkUser($user_id, $request->bearerToken()), 500);
        // User check

        $user = User::find($user_id);

        return response(Arrival::create(['user_id' => $user_id, 'user_name' => $user->name, 'date' => $request->date]), 200);
    });

    Route::delete('/arrivals/{arrival}', function (int $user_id, int $id, Request $request) {
        // User check
        if (checkUser($user_id, $request->bearerToken()) !== 200) return response(checkUser($user_id, $request->bearerToken()), 500);
        // User check

        $arrival = Arrival::find($id);

        if (!$arrival) return response("Arrival entry with this id ({$id}) does not exist.", 500);

        return response("Arrival entry deleted successfully!", Arrival::destroy($id) * 200);
    });
});