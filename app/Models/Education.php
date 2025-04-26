<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Education extends Model
{
    // app/Models/Education.php


    protected $fillable = [
        'employee_id',
        'tingkat',
        'nama_sekolah', 
        'tempat',
        'tahun_lulus',
        'jurusan',
        'keterangan',
        'sedang_ditempuh'
    ];

   
    protected $casts = [
        'sedang_ditempuh' => 'boolean',
    ];

    public function getStatusAttribute()
    {
        return $this->sedang_ditempuh ? 'Sedang Ditempuh' : 'Telah Lulus';
    }
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
