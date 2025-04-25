<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'nama',
        'deskripsi'
    ];
    
    // Opsional: Jika ingin relasi ke employee
    public function employees()
    {
        return $this->hasMany(Employee::class, 'departemen_id');
    }
}
