<?php

namespace App\Traits;

use App\Support\MediaUrl;

trait StoresMediaPaths
{
    public function getAttribute($key)
    {
        if ($this->isMediaPathAttribute($key) && array_key_exists($key, $this->attributes)) {
            return MediaUrl::toPublicUrl($this->attributes[$key]);
        }

        return parent::getAttribute($key);
    }

    public function setAttribute($key, $value)
    {
        if ($this->isMediaPathAttribute($key)) {
            $value = MediaUrl::toStoragePath($value);
        }

        return parent::setAttribute($key, $value);
    }

    public function getRawMediaPath(string $key): ?string
    {
        return $this->attributes[$key] ?? null;
    }

    protected function isMediaPathAttribute(string $key): bool
    {
        return in_array($key, $this->mediaPathAttributes(), true);
    }

    /** @return list<string> */
    protected function mediaPathAttributes(): array
    {
        return property_exists($this, 'mediaPathAttributes')
            ? $this->mediaPathAttributes
            : [];
    }
}
