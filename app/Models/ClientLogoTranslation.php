<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientLogoTranslation extends Model
{
    protected $fillable = ['client_logo_id', 'locale', 'display_name'];

    public function clientLogo(): BelongsTo
    {
        return $this->belongsTo(ClientLogo::class);
    }
}
