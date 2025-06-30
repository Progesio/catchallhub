<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\returnArgument;
//

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $data=DB::table('view_konten_argenta_flat')->get();
    // reformat data into json
    foreach ($data as $k=> $item){
        foreach ($item as $key => $value) {
            $decoded = json_decode($value, true);
            $item->$key = $decoded;
        }
        $data[$k]=$item;
    }
    return response()->json([
        'data' => $data,
        'message' => 'success',
        'status' => 200
    ]);
});
Route::get('/debug', function () {
    $data=DB::table('view_konten_argenta_flat')->get();
    return response()->json($data);
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
