<?php

use App\Models\ProgramKerja;
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
        Schema::create('tim_monevs', function (Blueprint $table) {
            $table->foreignIdFor(User::class, 'id_user')->constrained('users', 'id')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('tim_monevs');
    }
};
