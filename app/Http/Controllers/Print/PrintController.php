<?php

namespace App\Http\Controllers\Print;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    //
    public function printDaftarHadir($session_id)
    {
        return view('print.daftar-hadir', [
            'session_id' => $session_id,
        ]);
    }

    public function printBeritaAcara($session_id)
    {
        return view('print.berita-acara', [
            'session_id' => $session_id,
        ]);
    }
}
