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
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('jabatan_id')->constrained('jabatan');
            $table->date('tanggal_bergabung');
            $table->date('tanggal_lahir');
            $table->string('tempat_lahir');
            $table->string('no_rekening');
            $table->string('jenis_kelamin');
            $table->string('alamat');
            $table->string('no_hp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};
