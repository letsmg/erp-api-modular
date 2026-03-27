<?php

namespace App\Modules\Client\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'zip_code', 'street', 'number', 'neighborhood', 'city', 'state', 'complement',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
