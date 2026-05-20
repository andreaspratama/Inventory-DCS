<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function index()
    {
        return view('scan.index');
    }

    public function process(Request $request)
    {
        try {

            $code = $request->code;

            // coba decode JSON dulu
            $dataQR = json_decode($code, true);

            if ($dataQR && isset($dataQR['id'])) {
                $data = Barang::find($dataQR['id']);
            } else {
                // fallback kalau bukan JSON
                $data = Barang::where('qr_code', $code)->first();
            }

            if (!$data) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }

            return response()->json([
                'status' => true,
                'nama' => $data->nama_barang
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
