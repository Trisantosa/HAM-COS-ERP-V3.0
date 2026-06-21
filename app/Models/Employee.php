<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\RowLevelScopeable;

class Employee extends Model
{
    use HasFactory, SoftDeletes, RowLevelScopeable;

    protected $fillable = [
        'user_id',
        'division_id',
        'nik',
        'position',
        'hire_date',
        'birth_date',
        'gender',
        'phone',
        'address',
        'city',
        'province',
        'has_certification',
        'bank_account_verified',
        'bank_name',
        'bank_account_number',
        'bank_account_holder_name',
        'basic_salary',
        'allowance',
        'is_active',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'birth_date' => 'date',
        'has_certification' => 'boolean',
        'bank_account_verified' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCertification($query, bool $certified = true)
    {
        return $query->where('has_certification', $certified);
    }

    public function scopeByDivision($query, int $divisionId)
    {
        return $query->where('division_id', $divisionId);
    }

    public function isCertified(): bool
    {
        return $this->has_certification;
    }

    public function isBankAccountVerified(): bool
    {
        return $this->bank_account_verified;
    }

    public function getFullName(): string
    {
        return $this->user->name;
    }
}
