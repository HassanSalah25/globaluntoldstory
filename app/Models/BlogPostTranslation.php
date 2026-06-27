<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogPostTranslation extends Model
{
    protected $fillable = ['blog_post_id', 'locale', 'title', 'excerpt', 'body', 'tags'];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
        ];
    }

    public function blogPost(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class);
    }
}
