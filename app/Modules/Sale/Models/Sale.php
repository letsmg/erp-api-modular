<?php

namespace App\Modules\Sale\Models;

use App\Modules\Client\Models\Client;
use Database\Factories\SaleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'total_amount', 'sale_date', 'status'];
    protected $casts = ['total_amount' => 'decimal:2', 'sale_date' => 'datetime'];

    protected static function newFactory(): SaleFactory
    {
        return SaleFactory::new();
    }

    public function client(): BelongsTo { return $this->belongsTo(Client::class); }
    public function items(): HasMany { return $this->hasMany(SaleItem::class); }
}
