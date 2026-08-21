<?php

namespace App\Http\Controllers;

use App\Models\Aspirasi;
use Illuminate\Http\Request;

class AspirasiController extends Controller
{
    public function index()
    {
        return view('webpage.aspirasi');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'prodi' => 'required|string',
            'kategori' => 'required|string',
            'deskripsi' => 'required',
            'dokumentasi.*' => 'nullable|file|image|mimes:png,jpg,jpeg,webp',
        ]);
        $cleanDescription = strip_tags($data['deskripsi'], '<p><br><strong><em><ul><ol><li><a>');
        try {
            $dokumentasiPaths = [];
            if ($request->hasFile('dokumentasi')) {
                foreach ($request->file('dokumentasi') as $image) {
                    $timestamp = now()->timestamp;
                    $filename = $timestamp . '-' . $image->getClientOriginalName();
                    try {
                        $path = $image->storeAs('dokum-aspirasi', $filename, 'public');
                        $dokumentasiPaths[] = $path;
                    } catch (\Exception $e) {
                        throw $e;
                    }
                }
            }
            $aspirasi = Aspirasi::create([
                'id_prodi' => match ($data['prodi']) {
                    'Kimia' => 1,
                    'Fisika' => 2,
                    'Biologi' => 3,
                    'Matematika' => 4,
                    'Farmasi' => 5,
                    'Informatika' => 6,
                },
                'id_kategori' => match ($data['kategori']) {
                    'Akademik' => 1,
                    'Kemahasiswaan' => 2,
                    'Administrasi' => 3,
                    'Fasilitas' => 4,
                    'Informasi' => 5,
                    'Lembaga Mahasiswa' => 6,
                },
                'description' => $cleanDescription,
                'photos' => $dokumentasiPaths,
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Aspirasi berhasil dikirim'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengirim aspirasi: ' . $e->getMessage()
            ], 500);
        }
    }
}
