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
        Schema::table('jenis_potongan_gaji', function (Blueprint $table) {
            $table->boolean('isWajib')->default(false)->after('jumlah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_potongan_gaji', function (Blueprint $table) {
            //
        });
    }
};
