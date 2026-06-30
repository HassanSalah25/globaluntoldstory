<?php

namespace App\Services\Media;

use App\Models\BlogPost;
use App\Models\HeroSlide;
use App\Models\MediaAsset;
use App\Models\PageSection;
use App\Models\PortfolioItem;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Support\MediaUrl;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class FrontendMediaImporter
{
    /** @var array<string, string>|null */
    private static ?array $resolvedPaths = null;

    public function definitions(): array
    {
        return require database_path('data/frontend-media.php');
    }

    /**
     * @return array<string, string> Map of registry key => storage-relative path
     */
    public function import(bool $force = false): array
    {
        $paths = [];

        foreach ($this->definitions() as $key => $definition) {
            $paths[$key] = $this->importOne($key, $definition, $force);
        }

        self::$resolvedPaths = $paths;

        return $paths;
    }

    public static function resolvedPath(string $key): string
    {
        if (self::$resolvedPaths !== null && isset(self::$resolvedPaths[$key])) {
            return self::$resolvedPaths[$key];
        }

        $definitions = require database_path('data/frontend-media.php');

        if (! isset($definitions[$key])) {
            throw new \InvalidArgumentException("Unknown frontend media key: {$key}");
        }

        $definition = $definitions[$key];

        $asset = MediaAsset::query()
            ->where('folder', $definition['folder'])
            ->where('filename', $definition['filename'])
            ->first();

        if ($asset) {
            return $asset->path;
        }

        return "media/{$definition['folder']}/{$definition['filename']}";
    }

    public static function resolvedUrl(string $key): string
    {
        $path = self::resolvedPath($key);
        $publicUrl = MediaUrl::toPublicUrl($path);

        if ($publicUrl !== null) {
            return $publicUrl;
        }

        $definitions = require database_path('data/frontend-media.php');

        return $definitions[$key]['source'];
    }

    public function syncContentReferences(): int
    {
        $photoMap = $this->buildPhotoIdMap($this->import());
        $updated = 0;

        HeroSlide::query()->each(function (HeroSlide $slide) use ($photoMap, &$updated) {
            $newPath = $this->resolvePath($slide->getRawMediaPath('image_url'), $photoMap);

            if ($newPath && $newPath !== $slide->getRawMediaPath('image_url')) {
                $slide->update(['image_url' => $newPath]);
                $updated++;
            }
        });

        PortfolioItem::query()->each(function (PortfolioItem $item) use ($photoMap, &$updated) {
            $newPath = $this->resolvePath($item->getRawMediaPath('image_url'), $photoMap);

            if ($newPath && $newPath !== $item->getRawMediaPath('image_url')) {
                $item->update(['image_url' => $newPath]);
                $updated++;
            }
        });

        Testimonial::query()->each(function (Testimonial $item) use ($photoMap, &$updated) {
            $newPath = $this->resolvePath($item->getRawMediaPath('avatar_url'), $photoMap);

            if ($newPath && $newPath !== $item->getRawMediaPath('avatar_url')) {
                $item->update(['avatar_url' => $newPath]);
                $updated++;
            }
        });

        BlogPost::query()->each(function (BlogPost $post) use ($photoMap, &$updated) {
            $changes = [];

            if ($newPath = $this->resolvePath($post->getRawMediaPath('author_image_url'), $photoMap)) {
                if ($newPath !== $post->getRawMediaPath('author_image_url')) {
                    $changes['author_image_url'] = $newPath;
                }
            }

            if ($newPath = $this->resolvePath($post->getRawMediaPath('featured_image_url'), $photoMap)) {
                if ($newPath !== $post->getRawMediaPath('featured_image_url')) {
                    $changes['featured_image_url'] = $newPath;
                }
            }

            if ($changes !== []) {
                $post->update($changes);
                $updated++;
            }
        });

        TeamMember::query()->each(function (TeamMember $member) use ($photoMap, &$updated) {
            $newPath = $this->resolvePath($member->getRawMediaPath('image_url'), $photoMap);

            if ($newPath && $newPath !== $member->getRawMediaPath('image_url')) {
                $member->update(['image_url' => $newPath]);
                $updated++;
            }
        });

        PageSection::query()->each(function (PageSection $section) use ($photoMap, &$updated) {
            $settings = $section->settings;

            if (! is_array($settings) || ! isset($settings['image'])) {
                return;
            }

            $currentPath = MediaUrl::toStoragePath($settings['image']);
            $newPath = $this->resolvePath($currentPath, $photoMap);

            if ($newPath && $newPath !== $currentPath) {
                $settings['image'] = $newPath;
                $section->update(['settings' => $settings]);
                $updated++;
            }
        });

        return $updated;
    }

    /**
     * @param  array<string, string>  $definition
     */
    private function importOne(string $key, array $definition, bool $force): string
    {
        $folder = $definition['folder'];
        $filename = $definition['filename'];
        $path = "media/{$folder}/{$filename}";

        $existing = MediaAsset::query()
            ->where('folder', $folder)
            ->where('filename', $filename)
            ->first();

        if ($existing && ! $force && Storage::disk($existing->disk)->exists($existing->path)) {
            return $existing->path;
        }

        $response = Http::timeout(90)
            ->withHeaders(['User-Agent' => 'GlobalUntoldStory/1.0'])
            ->get($definition['source']);

        if (! $response->successful()) {
            $fallbackSource = strtok($definition['source'], '?');
            $response = Http::timeout(90)
                ->withHeaders(['User-Agent' => 'GlobalUntoldStory/1.0'])
                ->get($fallbackSource);
        }

        if (! $response->successful()) {
            throw new \RuntimeException("Failed to download [{$key}] from {$definition['source']} (HTTP {$response->status()})");
        }

        Storage::disk('public')->put($path, $response->body());

        $mimeType = $response->header('Content-Type') ?: 'image/jpeg';
        $size = strlen($response->body());

        if ($existing) {
            if ($existing->path !== $path && Storage::disk($existing->disk)->exists($existing->path)) {
                Storage::disk($existing->disk)->delete($existing->path);
            }

            $existing->update([
                'path' => $path,
                'mime_type' => $mimeType,
                'size' => $size,
                'alt_text' => $definition['alt'] ?? null,
            ]);

            return $existing->fresh()->path;
        }

        $asset = MediaAsset::create([
            'disk' => 'public',
            'path' => $path,
            'filename' => $filename,
            'mime_type' => $mimeType,
            'size' => $size,
            'alt_text' => $definition['alt'] ?? null,
            'folder' => $folder,
            'uploaded_by' => null,
        ]);

        return $asset->path;
    }

    /**
     * @param  array<string, string>  $paths
     * @return array<string, string>
     */
    private function buildPhotoIdMap(array $paths): array
    {
        $map = [];

        foreach ($this->definitions() as $key => $definition) {
            $photoId = $this->extractPhotoId($definition['source']);

            if ($photoId) {
                $map[$photoId] = $paths[$key];
            }

            foreach ($definition['legacy_photo_ids'] ?? [] as $legacyId) {
                $map[$legacyId] = $paths[$key];
            }
        }

        return $map;
    }

    /**
     * @param  array<string, string>  $photoMap
     */
    private function resolvePath(?string $value, array $photoMap): ?string
    {
        if (! $value) {
            return null;
        }

        $photoId = $this->extractPhotoId($value);

        return $photoId ? ($photoMap[$photoId] ?? null) : null;
    }

    private function extractPhotoId(string $url): ?string
    {
        if (preg_match('/photo-(\d+-[a-f0-9]+)/i', $url, $matches)) {
            return 'photo-'.$matches[1];
        }

        return null;
    }
}
