<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\JadwalMonev;
use App\Models\ProgramKerja;
use Illuminate\Http\Request;
use App\Models\NotulensiMonev;
use App\Models\PenilaianMonev;
use Illuminate\Support\Facades\Response;

class DownloadController extends Controller
{
    public function downloadNotulensi(Request $request)
    {
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
        if ($request->id) {
            $notulensiId = $request->id;
            $notulensi = NotulensiMonev::find($notulensiId);
            $jadwal = JadwalMonev::find($notulensi->id_jadwal);
            $proker = ProgramKerja::find($jadwal->id_proker);
            $penilaian = PenilaianMonev::all();

            $filename = "Notulensi {$jadwal->name} {$proker->name}.docx";
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(public_path('notulensi-monev.docx'));

            $timMonev = collect($notulensi->tim_monev)
                ->sort()
                ->values()
                ->map(function($idUser, $index) {
                    $user = User::find(intval($idUser));
                    return ($index + 1) . ". {$user->name} ({$user->specifiedRole})";
                })
                ->join("\n");

            $templateProcessor->setValues([
                'jadwalKegiatan' => strtoupper($jadwal->name),
                'programKerja' => strtoupper($proker->name),
                'timMonev' => $timMonev,
                'tanggalKegiatan' => \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l, j F Y'),
                'waktuMulai' => \Carbon\Carbon::parse($notulensi->start_time)->translatedFormat('H:i'),
                'waktuSelesai' => \Carbon\Carbon::parse($notulensi->end_time)->translatedFormat('H:i'),
                'panitiaHadir' => $notulensi->kehadiran,
                'totalPanitia' => $proker->jumlah_panitia,
                'agendaKegiatan' => rtrim(
                    html_entity_decode(
                        strip_tags(
                            str_replace(
                                array("<li>", "</li>", "<p>", "</p>"), 
                                array("-  ", "\n", "", "\n"), 
                                $notulensi->agenda
                            )
                        ),
                        ENT_QUOTES | ENT_HTML5,
                        'UTF-8'
                    )
                , "\n"),
            ]);

            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $penilaian->count()) {
                    $templateProcessor->setValues([
                        "aspek{$i}" => $penilaian[$i - 1]->aspek,
                        "kriteria{$i}" => $penilaian[$i - 1]->kriteria,
                    ]);
                } else {
                    $templateProcessor->setValues([
                        "aspek{$i}" => "",
                        "kriteria{$i}" => "",
                    ]);
                }
            }

            $allScore = $notulensi->scores;
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $penilaian->count()) {
                    $penilaianId = $penilaian[$i - 1]->id;
                    for ($j = 1; $j <= 5; $j++) {
                        $scoreValue = isset($allScore[$penilaianId]) ? (is_array($allScore[$penilaianId]) ? $allScore[$penilaianId]['score'] : $allScore[$penilaianId]) : (isset($allScore[$i]) ? (is_array($allScore[$i]) ? $allScore[$i]['score'] : $allScore[$i]) : null);
                        $templateProcessor->setValues([
                            "{$i}{$j}" => (intval($scoreValue) === $j) ? '✔' : ''
                        ]);
                    }
                } else {
                    for ($j = 1; $j <= 5; $j++) {
                        $templateProcessor->setValues([
                            "{$i}{$j}" => ''
                        ]);
                    }
                }
            }

            $allDescription = $notulensi->descriptions;
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $penilaian->count()) {
                    $penilaianId = $penilaian[$i - 1]->id;
                    $descValue = isset($allDescription[$penilaianId]) ? (is_array($allDescription[$penilaianId]) ? $allDescription[$penilaianId]['description'] : $allDescription[$penilaianId]) : (isset($allDescription[$i]) ? (is_array($allDescription[$i]) ? $allDescription[$i]['description'] : $allDescription[$i]) : "");
                    $templateProcessor->setValues([
                        "deskripsi{$i}" => $descValue == "Belum ada penilaian yang ditambahkan." ? "" : $descValue
                    ]);
                } else {
                    $templateProcessor->setValues([
                        "deskripsi{$i}" => ""
                    ]);
                }
            }

            $allPhoto = $notulensi->photos;
            $templateProcessor->cloneBlock('block_dokumentasi', count($allPhoto), true, true);

            foreach ($allPhoto as $index => $photo) {
                $i = $index + 1;
                $pathOption1 = storage_path("app/public/{$photo}");
                $pathOption2 = public_path("storage/{$photo}");
                
                $finalImagePath = file_exists($pathOption1) ? $pathOption1 : (file_exists($pathOption2) ? $pathOption2 : null);

                if ($finalImagePath) {
                    $extension = strtolower(pathinfo($finalImagePath, PATHINFO_EXTENSION));
                    $supportedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                    
                    if (in_array($extension, $supportedExtensions)) {
                        try {
                            $templateProcessor->setImageValue("dokum_foto#{$i}", [
                                'path' => $finalImagePath,
                                'width' => 295,
                                'height' => 220,
                                'ratio' => false
                            ]);
                            $templateProcessor->setValue("dokum_keterangan#{$i}", "Gambar {$i}.");
                        } catch (\Exception $e) {
                            $templateProcessor->setValue("dokum_foto#{$i}", "");
                            $templateProcessor->setValue("dokum_keterangan#{$i}", "Gambar {$i}. (Gagal memuat foto)");
                        }
                    } else {
                        $templateProcessor->setValue("dokum_foto#{$i}", "");
                        $templateProcessor->setValue("dokum_keterangan#{$i}", "Gambar {$i}. (Format .{$extension} tidak didukung)");
                    }
                } else {
                    $templateProcessor->setValue("dokum_foto#{$i}", "");
                    $templateProcessor->setValue("dokum_keterangan#{$i}", "Gambar {$i}. (Foto tidak ditemukan)");
                }
            }

            $saveDirectory = storage_path("app/public/notulensi-monev");
            if (!is_dir($saveDirectory)) {
                mkdir($saveDirectory, 0755, true);
            }
            
            $filePath = $saveDirectory . "/{$filename}";
            $templateProcessor->saveAs($filePath);

            return response()->download($filePath, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'Content-Disposition' => "attachment; filename=\"{$filename}\""
            ]);
        }
    }
}
