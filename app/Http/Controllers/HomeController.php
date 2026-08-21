<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $blogs = Blog::where('divisi', 'Inti')->limit(3)->get();
        return view('webpage.home', compact('blogs'));
    }

    public function fungsio()
    {
        return view('webpage.fungsionaris');
    }

    public function proker()
    {
        $inti = Blog::where('divisi', 'Inti')->get();
        $komisi1 = Blog::where('divisi', 'Komisi 1')->get();
        $komisi2 = Blog::where('divisi', 'Komisi 2')->get();
        $komisi3 = Blog::where('divisi', 'Komisi 3')->get();
        $komisi4 = Blog::where('divisi', 'Komisi 4')->get();
        $komisi5 = Blog::where('divisi', 'Komisi 5')->get();
        return view('webpage.programKerja', compact('inti', 'komisi1', 'komisi2', 'komisi3', 'komisi4', 'komisi5'));
    }

    public function raker()
    {
        return view('webpage.rakergrab');
    }
}
