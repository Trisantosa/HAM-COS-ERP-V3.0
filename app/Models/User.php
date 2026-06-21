<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles, HasPermissions;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'is_active',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public function managed_divisions(): HasMany
    {
        return $this->hasMany(Division::class, 'manager_id');
    }

    public function payroll_approvals(): HasMany
    {
        return $this->hasMany(Payroll::class, 'approved_by');
    }

    public function audit_logs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function session_contexts(): HasMany
    {
        return $this->hasMany(SessionContext::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function hasPermissionTo($permission, $guardName = null)
    {
        return $this->hasRole('SUPERADMIN') || parent::hasPermissionTo($permission, $guardName);
    }
}
