<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    protected $fillable = [
        'employee_id',
        'nama',
        'hubungan',
        'alamat',
        'telepon',
        'pekerjaan_jabatan',
        'keterangan'
    ];
}
