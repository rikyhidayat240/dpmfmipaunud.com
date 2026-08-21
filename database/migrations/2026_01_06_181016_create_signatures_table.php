<?php

use App\Models\Lembaga;
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
        Schema::create('signatures', function (Blueprint $table) {
            $table->id();
            $table->string('nomor');
            $table->string('qr_code')->nullable();
            $table->string('unique_link')->unique();
            $table->text('file');
            $table->text('signed_file')->nullable();
            $table->text('keperluan');
            $table->dateTime('accepted_at')->nullable();
            $table->foreignIdFor(User::class, 'id_user')->constrained('users', 'id')->cascadeOnDelete();
            $table->foreignIdFor(Lembaga::class, 'id_lembaga')->constrained('lembagas', 'id')->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signatures');
    }
};
