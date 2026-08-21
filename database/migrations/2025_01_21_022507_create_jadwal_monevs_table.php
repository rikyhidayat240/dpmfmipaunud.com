<?php

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
        Schema::create('jadwal_monevs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('tanggal');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('jumlah_tim_monev');
            $table->string('lokasi');
            $table->boolean('notulen')->default(false);
            $table->foreignIdFor(ProgramKerja::class, 'id_proker')->constrained('program_kerjas', 'id')->onDelete('cascade')->onUpdate('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_monevs');
    }
};
