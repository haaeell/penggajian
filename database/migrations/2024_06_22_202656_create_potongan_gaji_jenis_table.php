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
        Schema::create('karyawan_jenis_potongan_gaji', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawan')->onDelete('cascade');
            $table->foreignId('jenis_potongan_gaji_id')->constrained('jenis_potongan_gaji')->onDelete('cascade');
            $table->foreignId('potongan_gaji_id')->constrained('potongan_gaji')->onDelete('cascade');
            $table->timestamps();
        });
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('potongan_gaji_jenis');
    }
};
