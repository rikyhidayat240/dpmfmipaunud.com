<?php

use App\Models\JadwalMonev;
use App\Models\User;
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
        Schema::create('notulensi_monevs', function (Blueprint $table) {
            $table->id();
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('kehadiran');
            $table->text('agenda');
            $table->json('scores')->nullable();
            $table->json('descriptions')->nullable();
            $table->json('photos')->nullable();
            $table->json('tim_monev')->nullable();
            $table->foreignIdFor(JadwalMonev::class, 'id_jadwal')->constrained('jadwal_monevs', 'id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignIdFor(User::class, 'id_user')->constrained('users', 'id')->onDelete('cascade')->onUpdate('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notulensi_monevs');
    }
};
