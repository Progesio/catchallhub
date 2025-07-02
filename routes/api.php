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


Route::get('/hub/argenta/mapubg', function () {
 $locations = DB::table('argenta_locations')->get();
    $features = $locations->map(function ($location) {
        return [
            'type' => 'Feature',
            'properties' => [
                'namaCabang'   => $location->nama_cabang,
                'namaPegawai'  => $location->nama_pegawai,
                'nomorTelepon' => $location->nomor_telepon,
                'jenisTitik'   => $location->jenis_titik,
                'warna'        => $location->warna,
                'timestamp'    => $location->waktu_input, // karena kamu pakai varchar
            ],
            'geometry' => [
                'type' => 'Point',
                'coordinates' => [
                    (float) $location->longitude,
                    (float) $location->latitude,
                ],
            ],
            'id' => (int) $location->id,
        ];
    });

    return response()->json([
        'type' => 'FeatureCollection',
        'features' => $features,
    ]);
});


Route::post('/hub/branch/pushmap', function (Request $request)
    {
        $validated = $request->validate([
            'id'            => 'required|numeric|unique:argenta_locations,id',
            'pointType'     => 'required|in:service-center,staf-teknis,cabang-penjualan',
            'latitude'      => 'required|numeric',
            'longitude'     => 'required|numeric',
            'branchName'    => 'required|string|max:255',
            'staffName'     => 'required|string|max:255',
            'phoneNumber'   => 'required|string|max:20',
            'timestamp'     => 'required|date',
            'color'         => 'required|string|max:7',
        ]);

        DB::table('argenta_locations')->insert([
            'id'            => $validated['id'],
            'jenis_titik'    => $validated['pointType'],
            'latitude'      => $validated['latitude'],
            'longitude'     => $validated['longitude'],
            'nama_cabang'   => $validated['branchName'],
            'nama_pegawai'    => $validated['staffName'],
            'nomor_telepon'  => $validated['phoneNumber'],
            'waktu_input'    => $validated['timestamp'],
            'warna'         => $validated['color'],
            'status'        => 'active',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        return response()->json([
            'message' => 'Location data saved successfully',
            'data' => $validated
        ], 201);
    });

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
