<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Ruang;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class RuangController extends Controller
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
            $query = Ruang::query();

            return Datatables::of($query)
                ->addColumn('aksi', function($item) {
                    return '
                        <a href="' . route('ruang.edit', $item->id) . '" class="btn btn-warning btn-sm">
                            <i class="fa fa-fw fa-pencil-alt"></i>
                        </a>
                        <a href="#" class="btn btn-danger btn-sm delete" data-id="'. $item->id .'">
                            <i class="fa fa-fw fa-times"></i>
                        </a>
                    ';
                })
                ->addColumn('unit_id', function($item) {
                    return $item->unit->nama;
                })
                ->addColumn('number', function($item) {
                    static $count = 0;
                    return ++$count;
                })
                ->rawColumns(['aksi', 'unit_id', 'number'])
                ->make();
        }

        return view('pages.admin.ruang.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $unit = Unit::all();

        return view('pages.admin.ruang.create', compact('unit'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'nama' => 'required|string|max:255',
        ]);

        // Simpan ke database
        Ruang::create([
            'unit_id' => $validated['unit_id'],
            'nama' => $validated['nama'],
        ]);

        // Redirect balik dengan pesan sukses
        return redirect()->route('ruang.index')->with('success', 'Lokasi berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Ruang::findOrFail($id);
        $unit = Unit::all();

        return view('pages.admin.ruang.edit', compact('item', 'unit'));
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
        // Validasi input
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'nama' => 'required|string|max:255',
        ]);

        // Cari data room
        $room = Ruang::findOrFail($id);

        // Update data
        $room->update([
            'unit_id' => $validated['unit_id'],
            'nama' => $validated['nama'],
        ]);

        // Redirect balik ke index dengan pesan sukses
        return redirect()->route('ruang.index')->with('success', 'Data lokasi berhasil diperbarui!');
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
        $item = Ruang::findOrFail($id);
        $item->delete();

        return redirect()->route('ruang.index')->with('success', 'Data Deleted Successfully');
    }
}
