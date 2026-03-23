<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TermAcceptance extends Model
{
    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'accepted_at',
        'term_version'
    ];
}