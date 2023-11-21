<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Tobiasfasanok\Users\Models\User;
use Tobiasfasanok\Arrivals\Models\Arrival;

Route::prefix('api/v1/users/{user}')->group(function () {
    Route::get('/arrivals', function (int $user_id) {
        $arrivals = [];
        foreach (Arrival::all() as $arrival) if ($arrival->user_id === $user_id) array_push($arrivals, $arrival);

        $arrivals = $arrivals;

        return ["arrivals" => $arrivals, "status" => 200];
    });

    Route::post('/arrivals', function (int $user_id, Request $request) {
        $user = User::find($user_id);

        return Arrival::create([
            'user_id' => $user_id,
            'user_name' => $user->name,
            'date' => $request->date,
        ]);
    });

    Route::delete('/arrivals/{arrival}', function (int $user_id, int $id) {
        $user = Arrival::find($id);

        if (!$user) return ["data" => "Arrival entry with this id ({$id}) does not exist.", "status" => 500];

        return ["message" => "Arrival entry deleted successfully!", "status" => Arrival::destroy($id) * 200];
    });
});

// use \October\Rain\Database\Model;

// $model = new Model();
// $model->bindEvent('model.afterCreate', function () {
//     \Log::info("{$model->name} was created!");
// });