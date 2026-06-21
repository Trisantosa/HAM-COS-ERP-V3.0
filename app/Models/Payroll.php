<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\RowLevelScopeable;

class Payroll extends Model
{
    use HasFactory, SoftDeletes, RowLevelScopeable;

    protected $fillable = [
        'payroll_period_id',
        'employee_id',
        'basic_salary',
        'allowance',
        'bonus',
        'deduction',
        'total_income',
        'status',
        'approved_by',
        'approved_at',
        'sent_at',
        'has_certification',
        'bank_verified',
        'rejection_reason',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'sent_at' => 'datetime',
        'has_certification' => 'boolean',
        'bank_verified' => 'boolean',
    ];

    public function period(): BelongsTo
    {
        return $this->belongsTo(PayrollPeriod::class, 'payroll_period_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPeriod($query, $periodId)
    {
        return $query->where('payroll_period_id', $periodId);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'APPROVED');
    }

    public function scopeRejected($query)
    {
        return $query->whereNotNull('rejection_reason');
    }

    public function canApprove(): bool
    {
        return $this->status === 'DRAFT' && $this->has_certification && $this->bank_verified;
    }

    public function approve($userId): bool
    {
        return $this->update([
            'status' => 'APPROVED',
            'approved_by' => $userId,
            'approved_at' => now(),
        ]);
    }

    public function reject(string $reason): bool
    {
        return $this->update([
            'status' => 'DRAFT',
            'rejection_reason' => $reason,
            'approved_by' => null,
            'approved_at' => null,
        ]);
    }

    public function calculateTotal(): self
    {
        $this->total_income = $this->basic_salary + $this->allowance + $this->bonus - $this->deduction;
        return $this;
    }
}
