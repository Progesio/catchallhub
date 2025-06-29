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
    // dd($data);
    return response()->json($data);
});
Route::get('/debug', function () {
    $data=DB::table('view_konten_argenta_flat')->get();
    // dd($data);
    return response()->json($data);
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
