<?php

namespace App\Modules\User\Models;

use App\Enums\AccessLevel;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'access_level', 'is_active', 'last_login_ip', 'email_verified_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'access_level' => AccessLevel::class,
    ];

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    public function isAdmin(): bool { return $this->access_level?->isAdmin() ?? false; }
    public function isOperator(): bool { return $this->access_level?->isOperator() ?? false; }
    public function isClient(): bool { return $this->access_level?->isClient() ?? false; }
    public function isStaff(): bool { return $this->access_level?->isStaff() ?? false; }
    public function canManageProducts(): bool { return $this->access_level?->canManageProducts() ?? false; }
    public function canDelete(): bool { return $this->access_level?->canDelete() ?? false; }
}
