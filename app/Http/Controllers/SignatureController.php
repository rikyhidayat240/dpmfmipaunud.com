<?php

namespace App\Http\Controllers;

use App\Models\Lembaga;
use App\Models\Signature;
use App\Models\User;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;

class SignatureController extends Controller
{
    public function create()
    {
        $lembagas = Lembaga::all();
        return view('webpage.signature.formTTE', compact(['lembagas']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png',
            'keperluan' => 'required|string',
            'id_lembaga' => 'required|exists:lembagas,id',
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('signatures', 'public');
        } else {
            $filePath = null;
        }

        $unique_link = uniqid(env('APP_URL') . '/signature/', true);

        Signature::create([
            'nomor' => $request->input('nomor'),
            'unique_link' => $unique_link,
            'file' => $filePath,
            'keperluan' => $request->input('keperluan'),
            'id_user' => optional(User::where('specifiedRole', 'Ketua')->where('is_active', true)->latest()->first())->id,
            'id_lembaga' => $request->input('id_lembaga'),
        ]);

        return redirect()->back()->with('success', 'Permohonan Tanda Tangan Elektronik berhasil diajukan.');
    }

    public function search()
    {
        $lembagas = Lembaga::all();
        return view('webpage.signature.lacakTTE', compact(['lembagas']));
    }

    public function find(Request $request)
    {
        $request->validate([
            'nomor' => 'required|string|max:255',
            'id_lembaga' => 'required|exists:lembagas,id',
        ]);

        $signature = Signature::where('nomor', $request->input('nomor'))
            ->where('id_lembaga', $request->input('id_lembaga'))
            ->first();

        if ($signature) {
            if ($signature->signed_file === null) {
                return redirect()->back()->with('status', 'Tanda tangan elektronik sedang diproses.');
            }

            return redirect()->back()->with([
                'signed_file' => $signature->signed_file,
                'nomor' => $signature->nomor,
                'id_lembaga' => $signature->id_lembaga,
                'status' => 'Tanda tangan elektronik telah diproses!'
            ]);
        } else {
            return redirect()->back()->with('error', 'Nomor surat tidak ditemukan.');
        }
    }

    public function show($unique_link)
    {
        $full_link = env('APP_URL') . '/signature/' . $unique_link;
        $signature = Signature::where('unique_link', $full_link)->first();

        if (!$signature) {
            return to_route('home');
        }

        return view('webpage.signature.verifikasiTTE', ['signature' => $signature]);
    }

    public function downloadFromPath(Request $request)
    {
        $path = $request->query('path');

        if (!$path) {
            return response()->json(['message' => 'Path tidak ditemukan.'], 400);
        }

        $pathsToCheck = [
            public_path('storage/' . $path),
            storage_path('app/public/' . $path)
        ];

        foreach ($pathsToCheck as $fullPath) {
            if (file_exists($fullPath)) {
                $fileName = basename($path);
                return response()->download($fullPath, $fileName);
            }
        }

        return response()->json(['message' => 'File tidak ditemukan (Cek apakah file terhapus di server).'], 404);
    }

    public function generateQRCode($unique_link, $nomor)
    {
        $options = new QROptions([
            'version'              => 7,
            'outputType'           => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'             => QRCode::ECC_H, // High error correction
            'scale'                => 10,
            'imageBase64'          => false,
            'imageTransparent'     => false,
            'quality'              => 100,
            'quietzoneSize'        => 0,
        ]);

        $qrcode = new QRCode($options);
        $qrCodeImage = $qrcode->render($unique_link);

        // Save to storage
        $subName = str_replace('/', '_', $nomor);
        $filename = 'qr-codes/' . $subName . '.png';
        
        $fullPath = public_path('storage/' . $filename);
        $directory = dirname($fullPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        file_put_contents($fullPath, $qrCodeImage);

        return $filename;
    }

    public function downloadQRCode($id)
    {
        $signature = Signature::find($id);
        if (!$signature) return response()->json(['message' => 'Data tidak ditemukan.'], 404);

        if ($signature->qr_code) {
            $pathsToCheck = [
                public_path('storage/' . $signature->qr_code),
                storage_path('app/public/' . $signature->qr_code)
            ];

            foreach ($pathsToCheck as $fullPath) {
                if (file_exists($fullPath)) {
                    $nomorSafe = str_replace('/', '_', $signature->nomor);
                    return response()->download($fullPath, $nomorSafe . '.png', ['Content-Type' => 'image/png']);
                }
            }
        }

        // Fallback backward compatibility
        $nomor = str_replace('/', '_', $signature->nomor);
        $filePath = 'qr-codes/' . $nomor . '.png';
        $fullPath = public_path('storage/' . $filePath);

        if (file_exists($fullPath)) {
            return response()->download($fullPath, $nomor . '.png', ['Content-Type' => 'image/png']);
        }

        return response()->json(['message' => 'File tidak ditemukan (Cek apakah file terhapus di server).'], 404);
    }

    public function downloadPDF($id)
    {
        $signature = Signature::find($id);

        if ($signature && $signature->signed_file) {
            $pathsToCheck = [
                public_path('storage/' . $signature->signed_file),
                storage_path('app/public/' . $signature->signed_file)
            ];

            foreach ($pathsToCheck as $fullPath) {
                if (file_exists($fullPath)) {
                    $subName = str_replace('/', '_', $signature->nomor);
                    return response()->download($fullPath, $subName . '_signed.pdf', ['Content-Type' => 'application/pdf']);
                }
            }
        }
        
        return response()->json(['message' => 'File tidak ditemukan (Cek apakah file terhapus di server).'], 404);
    }

    public function insertQRCodeToPDF($pdfPath, $qrCodePath, $outputPath, $symbol = '#')
    {
        try {
            $pdf = new Fpdi();
            $fullPdfPath = public_path('storage/' . $pdfPath);
            $fullQrPath = public_path('storage/' . $qrCodePath);

            if (!file_exists($fullPdfPath) || !file_exists($fullQrPath)) {
                return ['success' => false, 'message' => 'File tidak ditemukan.'];
            }

            $pageCount = $pdf->setSourceFile($fullPdfPath);
            $symbolFound = false;

            $parser = new \Smalot\PdfParser\Parser();
            $pdfParser = $parser->parseFile($fullPdfPath);
            $pages = $pdfParser->getPages();

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);

                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId);

                if (isset($pages[$pageNo - 1])) {
                    $page = $pages[$pageNo - 1];
                    try {
                        $textDetails = $page->getDataTm();
                    } catch (\Throwable $e) {
                        $textDetails = [];
                    }

                    $symbolPosition = null;
                    $symbolPosition = null;
                    $anchorPosition = null;
                    $lowestY = 999999;
                    $anchorLowestY = 999999;

                    foreach ($textDetails as $textData) {
                        $text = isset($textData[1]) ? $textData[1] : '';
                        $cleanText = preg_replace('/\s+/', '', $text);

                        if (isset($textData[0]) && is_array($textData[0]) && count($textData[0]) >= 6) {
                            $currentX = $textData[0][4];
                            $currentY = $textData[0][5];

                            if (strpos($cleanText, $symbol) !== false) {
                                $symbolFound = true;
                                if ($currentY < $lowestY) {
                                    $lowestY = $currentY;
                                    $symbolPosition = [
                                        'x' => $currentX,
                                        'y' => $currentY
                                    ];
                                }
                            }
                        }
                    }

                    if ($symbolPosition) {
                        $qrSize = 25;

                        $x_pt = $symbolPosition['x'];
                        $y_pt = $symbolPosition['y'];
                        
                        $x_mm = $x_pt * 0.352778;
                        $y_mm_from_bottom = $y_pt * 0.352778; 
                        
                        $y_fpdi = $size['height'] - $y_mm_from_bottom;

                        $rectSize = $qrSize;
                        $pdf->SetFillColor(255, 255, 255);
                        
                        if ($y_pt > 750) {
                            $y_fpdi = $size['height'] - 40;
                        }

                        $topLeftX = $x_mm - ($qrSize / 2);
                        $topLeftY = $y_fpdi - ($qrSize / 2) - 2;

                        $pdf->Rect($topLeftX, $topLeftY, $rectSize, $rectSize, 'F');
                        $pdf->Image($fullQrPath, $topLeftX, $topLeftY, $qrSize, $qrSize, 'PNG');
                    }
                }
            }

            if (!$symbolFound) {
                return ['success' => false, 'message' => 'Simbol "' . $symbol . '" tidak ditemukan dalam PDF.'];
            }

            $fullOutputPath = public_path('storage/' . $outputPath);

            $directory = dirname($fullOutputPath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $pdf->Output('F', $fullOutputPath);

            return ['success' => true, 'message' => 'Dokumen berhasil ditandatangani.', 'path' => $outputPath];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    public function signDocument($signatureId)
    {
        $signature = Signature::find($signatureId);

        if (!$signature) {
            return ['success' => false, 'message' => 'Signature tidak ditemukan.'];
        }

        if (!$signature->qr_code) {
            $qrCodePath = $this->generateQRCode($signature->unique_link, $signature->nomor);
            $signature->qr_code = $qrCodePath;
            $signature->save();
        } else {
            $qrCodePath = $signature->qr_code;
        }

        $outputPath = 'signed-file/' . pathinfo($signature->file, PATHINFO_FILENAME) . '_signed.pdf';

        $result = $this->insertQRCodeToPDF($signature->file, $qrCodePath, $outputPath);

        if ($result['success']) {
            $signature->signed_file = $outputPath;
            $signature->accepted_at = now();
            $signature->save();
        }

        return $result;
    }

    public function unsignDocument($signatureId)
    {
        $signature = Signature::find($signatureId);

        if (!$signature) {
            return ['success' => false, 'message' => 'Signature tidak ditemukan.'];
        }

        if ($signature->signed_file) {
            $signedPath = public_path('storage/' . $signature->signed_file);
            if (file_exists($signedPath)) {
                unlink($signedPath);
            }
        }

        if ($signature->qr_code) {
            $qrPath = public_path('storage/' . $signature->qr_code);
            if (file_exists($qrPath)) {
                unlink($qrPath);
            }
        }

        $signature->qr_code = null;
        $signature->signed_file = null;
        $signature->accepted_at = null;
        $signature->save();

        return ['success' => true, 'message' => 'Dokumen berhasil dibatalkan tanda tangannya.'];
    }
}
