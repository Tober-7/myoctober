<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Tobiasfasanok\Arrivals\Models\Arrival;

Route::prefix('api/v1')->group(function () {
    Route::get('/arrivals', function () {
        return Arrival::all();
    });

    Route::post('/arrivals', function (Request $request) {
        return Arrival::create([
            'user_id' => $request->user_id,
            'user_name' => $request->user_name,
            'date' => $request->date,
        ]);
    });

    Route::delete('/arrivals/{arrival}', function (int $id) {
        $user = Arrival::find($id);

        if (!$user) return ["data" => "Arrival entry with this id ({$id}) does not exist.", "status" => 500];

        // echo ["data" => "Arrival entry deleted successfully!", "status" => 200];
        Arrival::destroy($id);
    });
});

// use \October\Rain\Database\Model;

// $model = new Model();
// $model->bindEvent('model.afterCreate', function () {
//     \Log::info("{$model->name} was created!");
// });