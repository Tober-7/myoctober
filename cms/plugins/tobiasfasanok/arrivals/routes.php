<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Tobiasfasanok\Users\Models\User;
use Tobiasfasanok\Arrivals\Models\Arrival;

Route::prefix('api/v1/users/{user}')->group(function () {
    Route::get('/arrivals', function (int $user_id) {
        $user = User::find($user_id);

        if (!$user) return ["message" => "User with this id ({$user_id}) does not exist.", "status" => 500];

        $arrivals = [];
        foreach (Arrival::all() as $arrival) if ($arrival->user_id === $user_id) array_push($arrivals, $arrival);

        return ["arrivals" => $arrivals, "status" => 200];
    });

    Route::post('/arrivals', function (int $user_id, Request $request) {
        $user = User::find($user_id);

        if (!$user) return ["message" => "User with this id ({$user_id}) does not exist.", "status" => 500];

        return ["arrival" => Arrival::create(['user_id' => $user_id, 'user_name' => $user->name, 'date' => $request->date,]), "status" => 200];
    });

    Route::delete('/arrivals/{arrival}', function (int $user_id, int $id) {
        $user = Arrival::find($id);

        if (!$user) return ["message" => "Arrival entry with this id ({$id}) does not exist.", "status" => 500];

        return ["message" => "Arrival entry deleted successfully!", "status" => Arrival::destroy($id) * 200];
    });
});

function readJWT($token) {
    [$header, $payload, $signature] = explode($token, '.');
    $payload = base64_decode($payload);

    return $payload;
}

// use \October\Rain\Database\Model;

// $model = new Model();
// $model->bindEvent('model.afterCreate', function () {
//     \Log::info("{$model->name} was created!");
// });