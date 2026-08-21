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
        Schema::create('jadwal_tim_monevs', function (Blueprint $table) {
            $table->foreignIdFor(JadwalMonev::class, 'id_jadwal')->constrained('jadwal_monevs', 'id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignIdFor(User::class, 'id_user')->constrained('users', 'id')->onDelete('cascade')->onUpdate('cascade');
            $table->boolean('hadir')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_tim_monevs');
    }
};
