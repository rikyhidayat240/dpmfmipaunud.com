<?php

use App\Models\Lembaga;
use App\Models\ProgramKerja;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['Laporan Pertanggungjawaban', 'Laporan Pertanggungjawaban Keuangan', 'Term of References (TOR)', 'Rancangan Anggaran Biaya', 'Laporan Lainnya'])->default('Laporan Lainnya');
            $table->text('description')->nullable();
            $table->text('file');
            $table->foreignIdFor(Lembaga::class, 'id_lembaga')->constrained('lembagas', 'id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignIdFor(ProgramKerja::class, 'id_proker')->nullable()->constrained('program_kerjas', 'id')->onDelete('cascade')->onUpdate('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
