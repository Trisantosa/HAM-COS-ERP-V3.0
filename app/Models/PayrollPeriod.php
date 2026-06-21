<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PayrollPeriod extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'year',
        'month',
        'start_date',
        'end_date',
        'payment_date',
        'status',
        'created_by',
        'locked_by',
        'locked_at',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'payment_date' => 'date',
        'locked_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function locker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'locked_by');
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }

    public function scopeCurrent($query)
    {
        $now = now();
        return $query->where('year', $now->year)->where('month', $now->month);
    }

    public function scopeLocked($query)
    {
        return $query->where('status', 'LOCKED');
    }

    public function isLocked(): bool
    {
        return $this->status === 'LOCKED';
    }

    public function lock($userId): bool
    {
        return $this->update([
            'status' => 'LOCKED',
            'locked_by' => $userId,
            'locked_at' => now(),
        ]);
    }

    public function getPeriodString(): string
    {
        return sprintf('%02d/%04d', $this->month, $this->year);
    }
}
