<?php

use App\Models\KategoriSurat;
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
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['Surat Masuk', 'Surat Keluar']);
            $table->text('description')->nullable();
            $table->text('file');
            $table->foreignIdFor(Lembaga::class, 'id_lembaga')->nullable()->constrained('lembagas', 'id')->cascadeOnDelete();
            $table->foreignIdFor(ProgramKerja::class, 'id_proker')->nullable()->constrained('program_kerjas', 'id')->cascadeOnDelete();
            $table->foreignIdFor(KategoriSurat::class, 'id_kategori')->constrained('kategori_surats', 'id')->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};
