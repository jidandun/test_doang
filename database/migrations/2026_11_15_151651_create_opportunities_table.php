<?php

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
        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->string('jenis', 20)->index();
            $table->string('penyelenggara');
            $table->string('kategori', 100)->nullable();
            $table->string('biaya_tipe', 20)->default('gratis')->index();
            $table->unsignedInteger('nominal_biaya')->nullable();
            $table->unsignedTinyInteger('min_semester')->default(1)->index();
            $table->decimal('min_ipk', 3, 2)->default(0.00)->index();
            $table->date('deadline')->index();
            $table->string('url_pendaftaran', 500);
            $table->string('poster_path')->nullable();
            $table->text('deskripsi');
            $table->text('syarat');
            $table->text('hadiah_benefit')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opportunities');
    }
};
