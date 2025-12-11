<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asets;
use App\Models\Type;
use App\Models\Unit;
use App\Models\Ruang;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Spatie\Permission\Traits\HasRoles;


class AsetsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax())
        {
            $query = Asets::query();

            // ===== Filter berdasarkan role =====
            if (auth()->user()->role === 'sarpra') {
                // Tampilkan aset hanya sesuai unit user
                $query->where('unit_id', auth()->user()->unit_id);
            }

            $query->orderBy('id', 'desc');

            // Admin: tidak ada filter → semua data keluar

            return Datatables::of($query)
                ->addColumn('aksi', function($item) {
                    return '
                        <div class="d-flex justify-content-center gap-2">
                            <a href="' . route('asets.edit', $item->id) . '" class="btn btn-warning btn-sm">
                                <i class="fa fa-fw fa-pencil-alt"></i>
                            </a>
                            <a href="' . route('asets.show', $item->id) . '" class="btn btn-info btn-sm">
                                <i class="fa fa-info"></i>
                            </a>
                            <a href="#" class="btn btn-danger btn-sm delete" data-id="'. $item->id .'">
                                <i class="fa fa-fw fa-times"></i>
                            </a>
                        </div>
                    ';
                })
                ->addColumn('barcode', function ($row) {
                    $qrUrl = asset('storage/' . $row->barcode);
                    $downloadUrl = route('downloadQRMultiple', $row->id);

                    return '
                        <div style="text-align:center">
                            <img src="' . $qrUrl . '" width="80" alt="QR Code"><br>
                            <a href="' . $downloadUrl . '" class="btn btn-sm btn-primary mt-1">
                                <i class="fa fa-download"></i> Download
                            </a>
                        </div>
                    ';
                })
                ->addColumn('unit_id', function($item) {
                        return optional($item->unit)->nama ?? '-';
                    })
                    ->addColumn('ruang_id', function($item) {
                        return optional($item->ruang)->nama 
                            ?? $item->other_lokasi 
                            ?? '-';
                    })
                ->addColumn('type_id', fn($item) => $item->type->nama)
                ->addColumn('number', function($item) {
                    static $count = 0;
                    return ++$count;
                })
                ->rawColumns(['aksi', 'barcode'])
                ->make();
        }

        return view('pages.admin.asets.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $type = Type::all();
        $user = auth()->user();

        if ($user->role == 'admin') {
            $unit = Unit::all();
        } else {
            $unit = Unit::where('id', $user->unit_id)->get();
        }

        return view('pages.admin.asets.create', compact('type', 'unit'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string',
            'type_id' => 'required|integer',
            'unit_id' => 'required|integer',
            'brand' => 'required|string',
            'jumlah' => 'required|integer',
            'ruang_id' => 'nullable|string',
            'harga' => 'required|numeric',
            'tgl_beli' => 'required|date',
            'deskripsi' => 'nullable|string',
            'other_lokasi' => 'nullable|string',
        ]);

        // Jika pilih OTHER → ruang_id = null, pakai other_lokasi
        if ($request->ruang_id === 'OTHER') {
            $data['ruang_id'] = null;
            $tempat = $request->other_lokasi;
        } else {
            // Jika bukan OTHER → other_lokasi = null
            $data['other_lokasi'] = null;

            $ruang = \App\Models\Ruang::find($request->ruang_id);
            $tempat = $ruang ? $ruang->nama : 'Unknown';
        }

        // Buat QR Code
        $qrData = "Nama: {$request->nama}\n"
                . "Type ID: {$request->type_id}\n"
                . "Unit ID: {$request->unit_id}\n"
                . "Brand: {$request->brand}\n"
                . "Jumlah: {$request->jumlah}\n"
                . "Tanggal Beli: {$request->tgl_beli}\n"
                . "Ruang: {$tempat}\n"
                . "Deskripsi: {$request->deskripsi}";

        $fileName = 'qrcode_' . time() . '.png';
        $path = 'qrcodes/' . $fileName;

        Storage::disk('public')->put($path, QrCode::format('png')->size(300)->generate($qrData));

        $data['barcode'] = $path;

        // Simpan aset
        Asets::create($data);

        return redirect()->route('asets.index')->with('success', 'Data Berhasil Ditambahkan dan QR Code Dibuat');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Ambil data aset berdasarkan ID
        $aset = \App\Models\Asets::with(['type', 'unit', 'ruang'])->findOrFail($id);

        // Kirim ke view
        return view('pages.admin.asets.show', compact('aset'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Asets::findOrFail($id);
        $user = auth()->user();

        // Unit: admin -> semua, selain admin -> unit milik user (jika ada unit_id)
        if (isset($user->role) && $user->role === 'admin') {
            $unit = Unit::all();
        } else {
            // kalau user belum punya unit_id, ambil semua agar tidak kosong
            if (!empty($user->unit_id)) {
                $unit = Unit::where('id', $user->unit_id)->get();
            } else {
                $unit = Unit::all(); // fallback: tampilkan semua supaya tidak error
            }
        }

        $type = Type::all();

        // Ruang: ambil berdasarkan unit yang terpakai di $item (untuk prefill),
        // kalau item->unit_id kosong, pakai first unit dari $unit collection
        $unitIdForRuang = $item->unit_id ?? ($unit->first()->id ?? null);

        if ($unitIdForRuang) {
            $ruang = Ruang::where('unit_id', $unitIdForRuang)->get();
        } else {
            $ruang = collect(); // kosong tapi tetap collection
        }

        return view('pages.admin.asets.edit', compact('item', 'unit', 'type', 'ruang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'unit_id' => 'required',
            'type_id' => 'required',
            'nama' => 'required',
            'brand' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'harga' => 'required',
            'ruang_id' => 'required',
            'other_lokasi' => 'nullable|string'
        ]);

        $item = Asets::findOrFail($id);

        // Jika pilih OTHER
        if ($request->ruang_id === 'OTHER') {
            $item->ruang_id = null;
            $item->other_lokasi = $request->other_lokasi; // simpan lokasi custom
        } else {
            $item->ruang_id = $request->ruang_id;
            $item->other_lokasi = null; // pastikan tidak dobel
        }

        $item->unit_id = $request->unit_id;
        $item->type_id = $request->type_id;
        $item->nama = $request->nama;
        $item->brand = $request->brand;
        $item->harga = $request->harga;
        $item->tgl_beli = $request->tgl_beli;
        $item->deskripsi = $request->deskripsi;

        $item->save();

        return redirect()->route('asets.index')->with('success', 'Data asset berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function delete($id)
    {
        $item = Asets::findOrFail($id);
        $item->delete();

        return redirect()->route('asets.index')->with('success', 'Data Deleted Successfully');
    }

    public function downloadQRMultiple($id)
    {
        $aset = Asets::findOrFail($id);

        if (!$aset->barcode || !Storage::disk('public')->exists($aset->barcode)) {
            return redirect()->back()->with('error', 'QR Code tidak ditemukan');
        }

        $jumlah = $aset->jumlah ?? 1;

        // Ambil file QR asli
        $path = Storage::disk('public')->path($aset->barcode);

        // Buat ZIP
        $zipFileName = 'QR_' . str_replace(' ', '_', $aset->nama) . '_x' . $jumlah . '.zip';
        $zipPath = storage_path('app/public/qrcodes/' . $zipFileName);

        $zip = new \ZipArchive;
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {

            for ($i = 1; $i <= $jumlah; $i++) {
                $zip->addFile($path, "QR_{$aset->nama}_{$i}.png");
            }

            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }


    public function getRuang($unit_id)
    {
        $ruang = Ruang::where('unit_id', $unit_id)->get();

        return response()->json($ruang);
    }
}
