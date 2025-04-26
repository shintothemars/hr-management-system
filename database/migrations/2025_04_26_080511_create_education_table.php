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
        Schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->enum('tingkat', [
                'Dasar', 
                'Lanjutan Pertama', 
                'Lanjutan Atas',
                'Akademi',
                'Universitas',
                'Kursus-Kursus',
                'Lain-Lain'
            ]);
            $table->string('nama_sekolah');
            $table->string('tempat');
            $table->integer('tahun_lulus');
            $table->string('jurusan')->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('sedang_ditempuh')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education');
    }
};
