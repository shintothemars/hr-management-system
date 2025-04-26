<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkExperience extends Model
{
    protected $fillable = [
        'employee_id',
        'nama_perusahaan',
        'jabatan',
        'tanggal_mulai',
        'tanggal_selesai',
        'gaji',
        'alasan_keluar'
    ];
    
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];
}
