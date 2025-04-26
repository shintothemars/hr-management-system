<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    protected $fillable = [
        'employee_id',
        'nama',
        'hubungan',
        'tempat_lahir',
        'tanggal_lahir',
        'pendidikan',
        'alamat',
        'pekerjaan',
        'no_telepon',
        'emergency_contact',
        
    ];
    protected $casts = [
        'emergency_contact' => 'boolean',
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function getRelationLabel(): string
    {
        return 'Keluarga';
    }
}
