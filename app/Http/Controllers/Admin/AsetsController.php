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
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\PngEncoder;


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
        // ================= VALIDASI =================
        $data = $request->validate([
            'nama'          => 'required|string',
            'type_id'       => 'required|integer',
            'unit_id'       => 'required|integer',
            'brand'         => 'required|string',
            'jumlah'        => 'required|integer',
            'ruang_id'      => 'nullable|string',
            'harga'         => 'nullable|numeric',
            'tgl_beli'      => 'nullable|date',
            'deskripsi'     => 'nullable|string',
            'other_lokasi'  => 'nullable|string',
            'sumber'        => 'required|string',
            'kode_brg'        => 'required|string',
        ]);

        // ================= NORMALISASI DATA =================
        foreach ($data as $key => $value) {
            if ($value === '') {
                $data[$key] = null;
            }
        }

        // ================= HANDLE RUANG =================
        if ($request->ruang_id === 'OTHER') {
            $data['ruang_id'] = null;
            $tempat = $data['other_lokasi'];
        } else {
            $data['other_lokasi'] = null;
            $ruang = Ruang::find($request->ruang_id);
            $tempat = $ruang?->nama ?? 'Unknown';
        }

        // ================= DATA QR =================
        $qrData = "Nama: {$data['nama']}\n"
            . "Kode Barang: {$data['kode_brg']}\n"
            . "Type ID: {$data['type_id']}\n"
            . "Unit ID: {$data['unit_id']}\n"
            . "Brand: {$data['brand']}\n"
            . "Jumlah: {$data['jumlah']}\n"
            . "Harga: " . ($data['harga'] ?? '-') . "\n"
            . "Tanggal Beli: " . ($data['tgl_beli'] ?? '-') . "\n"
            . "Ruang: {$tempat}\n"
            . "Sumber: {$data['sumber']}";

        // ================= WARNA QR & TEKS =================
        if ($data['sumber'] === 'Pemerintah / BOS') {
            $qrColor   = [150, 0, 0];   // merah tua
            $textColor = '#8B0000';
        } else {
            $qrColor   = [0, 0, 0];     // hitam
            $textColor = '#000000';
        }

        // ================= GENERATE QR BASE =================
        $qrImage = QrCode::format('png')
            ->size(300)
            ->margin(2)
            ->color($qrColor[0], $qrColor[1], $qrColor[2])
            ->backgroundColor(255, 255, 255)
            ->generate($qrData);

        // ================= INTERVENTION IMAGE v3 =================
        $manager = new ImageManager(new Driver());

        $qr      = $manager->read((string) $qrImage);
        $canvas  = $manager->create(300, 360)->fill('#ffffff');

        // tempel QR
        $canvas->place($qr, 'top');

        // teks nama barang
        $canvas->text(
            $data['nama'],
            150,
            320,
            function ($font) use ($textColor) {
                $font->filename(public_path('fonts/arial.ttf'));
                $font->size(18);
                $font->color($textColor);
                $font->align('center');
            }
        );

        // ================= SIMPAN FILE =================
        $fileName = 'qrcode_' . time() . '.png';
        $path     = 'qrcodes/' . $fileName;

        Storage::disk('public')->put(
            $path,
            $canvas->encode(new PngEncoder())
        );

        $data['barcode'] = $path;

        // ================= SIMPAN KE DB =================
        Asets::create($data);

        // ================= REDIRECT =================
        return redirect()
            ->route('asets.index')
            ->with('success', 'Data aset berhasil ditambahkan dan QR Code berhasil dibuat');
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
        // ================= VALIDASI =================
        $data = $request->validate([
            'unit_id'       => 'required|integer',
            'type_id'       => 'required|integer',
            'nama'          => 'required|string',
            'brand'         => 'nullable|string',
            'harga'         => 'nullable|string',
            'tgl_beli'      => 'nullable|date',
            'sumber'        => 'required|string',
            'ruang_id'      => 'required|string',
            'other_lokasi'  => 'nullable|string',
        ]);

        // ================= NORMALISASI HARGA =================
        if (!empty($data['harga'])) {
            $data['harga'] = (int) preg_replace('/\D/', '', $data['harga']);
        } else {
            $data['harga'] = null;
        }

        $aset = Asets::findOrFail($id);

        // ================= HANDLE RUANG =================
        if ($request->ruang_id === 'OTHER') {
            $data['ruang_id'] = null;
            $tempat = $request->other_lokasi;
        } else {
            $data['other_lokasi'] = null;
            $ruang = Ruang::find($request->ruang_id);
            $tempat = $ruang?->nama ?? 'Unknown';
        }

        // ================= CEK PERUBAHAN =================
        $needRegenerateQr =
            $aset->nama !== $data['nama'] ||
            $aset->sumber !== $data['sumber'] ||
            $aset->harga != $data['harga'] ||
            $aset->ruang_id != $data['ruang_id'];

        // ================= UPDATE DATA =================
        $aset->update($data);

        // ================= REGENERATE QR =================
        if ($needRegenerateQr) {

            // DATA QR
            $qrData =
                "Nama: {$aset->nama}\n" .
                "Kode Barang: {$aset->kode_brg}\n" .
                "Brand: {$aset->brand}\n" .
                "Harga: " . ($aset->harga ? number_format($aset->harga, 0, ',', '.') : '-') . "\n" .
                "Tanggal Beli: " . ($aset->tgl_beli ?? '-') . "\n" .
                "Ruang: {$tempat}\n" .
                "Sumber: {$aset->sumber}";

            // WARNA QR
            $qrColor = $aset->sumber === 'Pemerintah / BOS'
                ? [150, 0, 0]   // merah
                : [0, 0, 0];    // hitam

            // HAPUS QR LAMA
            if ($aset->barcode && Storage::disk('public')->exists($aset->barcode)) {
                Storage::disk('public')->delete($aset->barcode);
            }

            // ================= GENERATE QR TEMP =================
            $tempQrPath = storage_path('app/temp_qr.png');

            file_put_contents(
                $tempQrPath,
                QrCode::format('png')
                    ->size(300)
                    ->margin(2)
                    ->color($qrColor[0], $qrColor[1], $qrColor[2])
                    ->backgroundColor(255, 255, 255)
                    ->generate($qrData)
            );

            // ================= CANVAS + TEKS =================
            $manager = new ImageManager(new Driver());

            $canvas = $manager->create(300, 360)->fill('#ffffff');
            $qr     = $manager->read($tempQrPath);

            // tempel QR
            $canvas->place($qr, 'top');

            // teks nama barang
            $canvas->text($aset->nama, 150, 320, function ($font) {
                $font->size(18);
                $font->filename(public_path('fonts/arial.ttf'));
                $font->color('#000000');
                $font->align('center');
                $font->valign('middle');
            });

            // ================= SIMPAN =================
            $fileName = 'qrcode_' . time() . '.png';
            $path = 'qrcodes/' . $fileName;

            Storage::disk('public')->put(
                $path,
                (string) $canvas->encode(new PngEncoder())
            );

            @unlink($tempQrPath);

            $aset->update([
                'barcode' => $path
            ]);
        }

        return redirect()
            ->route('asets.index')
            ->with('success', 'Data aset & QR Code berhasil diperbarui');
    }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'unit_id' => 'required',
    //         'type_id' => 'required',
    //         'nama' => 'required',
    //         'brand' => 'nullable|string',
    //         'sumber' => 'required|string',
    //         'ruang_id' => 'required',
    //         'other_lokasi' => 'nullable|string'
    //     ]);

    //     $item = Asets::findOrFail($id);

    //     // Jika pilih OTHER
    //     if ($request->ruang_id === 'OTHER') {
    //         $item->ruang_id = null;
    //         $item->other_lokasi = $request->other_lokasi; // simpan lokasi custom
    //     } else {
    //         $item->ruang_id = $request->ruang_id;
    //         $item->other_lokasi = null; // pastikan tidak dobel
    //     }

    //     $item->unit_id = $request->unit_id;
    //     $item->type_id = $request->type_id;
    //     $item->nama = $request->nama;
    //     $item->kode_brg = $request->kode_brg;
    //     $item->brand = $request->brand;
    //     $item->harga = $request->harga;
    //     $item->tgl_beli = $request->tgl_beli;
    //     $item->sumber = $request->sumber;

    //     $item->save();

    //     return redirect()->route('asets.index')->with('success', 'Data asset berhasil diperbarui!');
    // }

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
