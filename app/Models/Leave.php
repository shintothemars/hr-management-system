<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'type',
        'start_date',
        'end_date',
        'days',
        'reason',
        'proof_path',
        'status',
        'notes'
    ];

    // Status yang tersedia
    public const STATUSES = [
        'pending' => 'Menunggu',
        'approved_by_hr' => 'Disetujui HR',
        'approved_by_manager' => 'Disetujui Manager',
        'approved_by_leader' => 'Disetujui Pimpinan',
        'rejected' => 'Ditolak'
    ];

    // Relasi ke karyawan
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    // Relasi ke approver (jika perlu)
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Format status untuk tampilan
    public function getStatusTextAttribute()
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    // Cek apakah bisa di-approve oleh role tertentu
    public function canBeApprovedBy($role)
    {
        return match ($role) {
            'hr' => $this->status === 'pending',
            'manager' => $this->status === 'approved_by_hr',
            'leader' => $this->status === 'approved_by_manager',
            default => false
        };
    }

    protected static function booted()
{
    static::creating(function ($leave) {
        if (is_null($leave->days)) {
            $leave->days = Carbon::parse($leave->start_date)->diffInDays($leave->end_date) + 1;
        }
    });
}
}
