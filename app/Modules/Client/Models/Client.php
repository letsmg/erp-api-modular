<?php

namespace App\Modules\Client\Models;

use App\Modules\Sale\Models\Sale;
use App\Modules\User\Models\User;
use Database\Factories\ClientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'document_number', 'phone', 'phone1', 'contact1', 'phone2', 'contact2'];

    protected static function newFactory(): ClientFactory
    {
        return ClientFactory::new();
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function address(): HasOne { return $this->hasOne(Address::class); }
    public function sales(): HasMany { return $this->hasMany(Sale::class); }
}
