<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactRequest extends Model
{
    protected $fillable = [
        'reference_id', 'name', 'email', 'phone', 'service', 'budget',
        'message', 'locale', 'ip', 'user_agent', 'status', 'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }
}
