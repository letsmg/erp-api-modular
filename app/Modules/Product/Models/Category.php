<?php

namespace App\Modules\Product\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'parent_id', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    protected static function newFactory(): CategoryFactory
    {
        return CategoryFactory::new();
    }

    protected static function booted(): void
    {
        static::creating(function ($category) { if (! $category->slug) { $category->slug = self::generateUniqueSlug($category->name); } });
        static::updating(function ($category) { if ($category->isDirty('name')) { $category->slug = self::generateUniqueSlug($category->name); } });
    }

    public function products(): HasMany { return $this->hasMany(Product::class); }
    public function parent(): BelongsTo { return $this->belongsTo(Category::class, 'parent_id'); }
    public function children(): HasMany { return $this->hasMany(Category::class, 'parent_id'); }

    public static function generateUniqueSlug($text): string
    {
        $baseSlug = Str::slug($text); $slug = $baseSlug; $count = 1;
        while (self::where('slug', $slug)->exists()) { $slug = $baseSlug.'-'.$count++; }
        return $slug;
    }
}
