<?php

namespace App\Modules\Product\Models;

use Database\Factories\SupplierFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name', 'cnpj', 'state_registration', 'address', 'neighborhood', 'city', 'zip_code',
        'state', 'email', 'contact_name_1', 'phone_1', 'contact_name_2', 'phone_2', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    protected static function newFactory(): SupplierFactory
    {
        return SupplierFactory::new();
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
