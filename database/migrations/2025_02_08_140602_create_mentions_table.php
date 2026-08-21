<?php

use App\Models\User;
use App\Models\Arsip;
use App\Models\Surat;
use App\Models\Laporan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mentions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'id_user')->constrained('users', 'id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignIdFor(Surat::class, 'id_surat')->nullable()->constrained('surats', 'id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignIdFor(Laporan::class, 'id_laporan')->nullable()->constrained('laporans', 'id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignIdFor(Arsip::class, 'id_arsip')->nullable()->constrained('arsips', 'id')->onDelete('cascade')->onUpdate('cascade');
            $table->json('users_mentioned');
            $table->text('notes');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentions');
    }
};
