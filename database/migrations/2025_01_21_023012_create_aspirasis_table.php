<?php

use App\Models\KategoriAspirasi;
use App\Models\ProgramStudi;
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
        Schema::create('aspirasis', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->text('photos')->nullable();
            $table->foreignIdFor(ProgramStudi::class, 'id_prodi')->constrained('program_studis', 'id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignIdFor(KategoriAspirasi::class, 'id_kategori')->constrained('kategori_aspirasis', 'id')->onDelete('cascade')->onUpdate('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aspirasis');
    }
};
