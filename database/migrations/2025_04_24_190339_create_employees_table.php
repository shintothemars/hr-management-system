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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('foto_profil')->nullable();
            $table->string('nama');
            $table->string('nama_panggilan');
            $table->string('email')->unique();
            $table->string('no_telepon');
            $table->string('tempat_lahir'); // Hanya satu kali
            $table->date('tanggal_lahir');
            $table->string('alamat_identitas');
            $table->string('alamat_domisili'); // Diperbaiki penulisannya
            $table->string('no_telepon_rumah');
            $table->string('status_keluarga');
            $table->string('jumlah_anak');
            $table->string('tinggi_badan');
            $table->string('berat_badan');
            $table->string('no_ktp');
            $table->string('masa_berlaku_ktp');
            $table->string('jabatan');
            $table->string('golongan_darah');
            $table->string('agama');
            $table->foreignId('departemen_id')->constrained('departments');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
