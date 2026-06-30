<?php

namespace App\Support;

class MediaUrl
{
    /**
     * Normalize any input (full URL, /storage/ path, or storage-relative path)
     * to a storage-relative path for database persistence.
     */
    public static function toStoragePath(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $value = trim($value);

        if (self::isExternalUrl($value)) {
            return $value;
        }

        if (preg_match('#^https?://#i', $value)) {
            $value = parse_url($value, PHP_URL_PATH) ?? $value;
        }

        $value = ltrim($value, '/');

        if (str_starts_with($value, 'storage/')) {
            $value = substr($value, strlen('storage/'));
        }

        return $value !== '' ? $value : null;
    }

    /**
     * Build a public URL from a stored path using the configured media base URL.
     */
    public static function toPublicUrl(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (self::isExternalUrl($value)) {
            return $value;
        }

        $path = self::toStoragePath($value);

        if ($path === null) {
            return null;
        }

        return rtrim(self::baseUrl(), '/').'/'.ltrim($path, '/');
    }

    public static function baseUrl(): string
    {
        return rtrim((string) config('filesystems.disks.public.url'), '/');
    }

    private static function isExternalUrl(string $value): bool
    {
        if (! preg_match('#^https?://#i', $value)) {
            return false;
        }

        $path = parse_url($value, PHP_URL_PATH) ?? '';

        if (str_contains($path, '/storage/')) {
            return false;
        }

        $host = parse_url($value, PHP_URL_HOST);
        $knownHosts = array_filter([
            parse_url((string) config('app.url'), PHP_URL_HOST),
            parse_url(self::baseUrl(), PHP_URL_HOST),
        ]);

        foreach ($knownHosts as $knownHost) {
            if ($host && strcasecmp($host, $knownHost) === 0) {
                return false;
            }
        }

        return true;
    }
}
