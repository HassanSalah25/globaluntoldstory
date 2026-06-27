<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerLabelTranslation extends Model
{
    protected $fillable = ['partner_label_id', 'locale', 'label'];

    public function partnerLabel(): BelongsTo
    {
        return $this->belongsTo(PartnerLabel::class);
    }
}
