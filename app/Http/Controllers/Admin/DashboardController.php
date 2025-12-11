<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Type;
use App\Models\Asets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Hitung jumlah tipe (tetap global)
        $type = Type::count();

        // Hitung jumlah aset, dibedakan jika user sarpra
        if ($user->role === 'sarpra') {
            // Aset yang sesuai dengan unit user yang login
            $asets = Asets::where('unit_id', $user->unit_id)->count();
        } else {
            // Selain sarpra → tampilkan semua
            $asets = Asets::count();
        }

        return view('pages.admin.dashboard', compact('type', 'asets'));
    }
}
