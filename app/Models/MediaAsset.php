<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Support\MediaUrl;

class MediaAsset extends Model
{
    protected $fillable = [
        'disk', 'path', 'filename', 'mime_type', 'size',
        'alt_text', 'folder', 'uploaded_by',
    ];

    protected $appends = ['url'];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getUrlAttribute(): string
    {
        return MediaUrl::toPublicUrl($this->path) ?? '';
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type ?? '', 'image/');
    }
}
