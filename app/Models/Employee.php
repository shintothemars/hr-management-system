<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'foto_profil', // Tambahkan ini
        'nama',
        'nama_panggilan',
        'email',
        'no_telepon',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat_identitas',
        'alamat_domisili',
        'no_telepon_rumah',
        'status_keluarga',
        'jumlah_anak',
        'tinggi_badan',
        'berat_badan',
        'no_ktp',
        'masa_berlaku_ktp',
        'jabatan',
        'golongan_darah',
        'agama',
        'departemen_id'
    ];

    protected $casts = [
        'foto_profil' => 'string',
    ];
    
    public static function rules()
    {
        return [
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }
    
    public function departemen()
    {
        return $this->belongsTo(Department::class, 'departemen_id');
    }
}
