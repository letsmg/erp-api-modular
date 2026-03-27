<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Seo extends Model
{
    use HasFactory;

    protected $table = 'seo';

    protected $fillable = [
        'meta_title', 'meta_description', 'meta_keywords', 'h1', 'text1',
        'h2', 'text2', 'schema_markup', 'google_tag_manager',
    ];

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }
}
