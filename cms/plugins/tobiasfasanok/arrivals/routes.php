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

        if (!$user) return "Arrival entry with this id ({$id}) does not exist.";

        echo "Arrival entry deleted successfully!";
        Arrival::destroy($id);
    });
});

// use \October\Rain\Database\Model;

// $model = new Model();
// $model->bindEvent('model.afterCreate', function () {
//     \Log::info("{$model->name} was created!");
// });