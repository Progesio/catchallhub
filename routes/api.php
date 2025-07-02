<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\returnArgument;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/hub/argenta/index', function () {
    $data=DB::table('view_konten_argenta_flat')->get();
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

// call all berita
Route::get('/hub/argenta/berita', function () {
    $data = DB::table('argenta_beritas')->get();
    return response()->json([
        'data' => $data,
        'message' => 'success',
        'status' => 200
    ]);
});

// call all karir
Route::get('/hub/argenta/karir', function () {
    $data = DB::table('argenta_karirs')->get();
    return response()->json([
        'data' => $data,
        'message' => 'success',
        'status' => 200
    ]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
