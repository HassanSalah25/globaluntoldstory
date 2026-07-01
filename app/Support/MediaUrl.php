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

        $value = self::extractStorageRelativePath(ltrim($value, '/'));

        return $value !== '' ? $value : null;
    }

    /**
     * Reduce any URL or path variant to a storage-relative path (e.g. media/2026/07/file.webp).
     * Handles subdirectory installs where paths may include api/public/storage/ prefixes.
     */
    private static function extractStorageRelativePath(string $value): string
    {
        while (preg_match('#^(?:api/public/)?storage/(.+)$#', $value, $matches)) {
            $value = $matches[1];
        }

        return $value;
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
