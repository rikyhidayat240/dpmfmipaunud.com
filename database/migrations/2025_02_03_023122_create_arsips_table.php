<?php

use App\Models\KategoriArsip;
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
        Schema::create('arsips', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('link')->nullable();
            $table->json('file')->nullable();
            $table->foreignIdFor(User::class, 'id_user')->constrained('users', 'id')->nullOnDelete()->cascadeOnDelete();
            $table->foreignIdFor(KategoriArsip::class, 'id_kategori')->constrained('kategori_arsips', 'id')->nullOnDelete()->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsips');
    }
};
