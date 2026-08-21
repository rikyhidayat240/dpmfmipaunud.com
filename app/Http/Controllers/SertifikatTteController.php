<?php

namespace App\Http\Controllers;

use App\Models\SertifikatTte;
use Illuminate\Http\Request;

class SertifikatTteController extends Controller
{
    public function show($unique_link)
    {
        $full_link = env('APP_URL') . '/sertifikat/' . $unique_link;
        $signature = SertifikatTte::where('unique_link', $full_link)->first();

        if (!$signature) {
            return to_route('home');
        }

        return view('webpage.sertifikat.verifikasiTTE', ['signature' => $signature]);
    }
}
