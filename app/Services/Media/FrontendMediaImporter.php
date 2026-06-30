<?php

namespace App\Services\Media;

use App\Models\BlogPost;
use App\Models\HeroSlide;
use App\Models\MediaAsset;
use App\Models\PageSection;
use App\Models\PortfolioItem;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class FrontendMediaImporter
{
    /** @var array<string, string>|null */
    private static ?array $resolvedUrls = null;

    public function definitions(): array
    {
        return require database_path('data/frontend-media.php');
    }

    /**
     * @return array<string, string> Map of registry key => public URL
     */
    public function import(bool $force = false): array
    {
        $urls = [];

        foreach ($this->definitions() as $key => $definition) {
            $urls[$key] = $this->importOne($key, $definition, $force);
        }

        self::$resolvedUrls = $urls;

        return $urls;
    }

    public static function resolvedUrl(string $key): string
    {
        if (self::$resolvedUrls !== null && isset(self::$resolvedUrls[$key])) {
            return self::$resolvedUrls[$key];
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
            return $asset->url;
        }

        return $definition['source'];
    }

    public function syncContentReferences(): int
    {
        $photoMap = $this->buildPhotoIdMap($this->import());
        $updated = 0;

        HeroSlide::query()->each(function (HeroSlide $slide) use ($photoMap, &$updated) {
            $newUrl = $this->resolveUrl($slide->image_url, $photoMap);

            if ($newUrl && $newUrl !== $slide->image_url) {
                $slide->update(['image_url' => $newUrl]);
                $updated++;
            }
        });

        PortfolioItem::query()->each(function (PortfolioItem $item) use ($photoMap, &$updated) {
            $newUrl = $this->resolveUrl($item->image_url, $photoMap);

            if ($newUrl && $newUrl !== $item->image_url) {
                $item->update(['image_url' => $newUrl]);
                $updated++;
            }
        });

        Testimonial::query()->each(function (Testimonial $item) use ($photoMap, &$updated) {
            $newUrl = $this->resolveUrl($item->avatar_url, $photoMap);

            if ($newUrl && $newUrl !== $item->avatar_url) {
                $item->update(['avatar_url' => $newUrl]);
                $updated++;
            }
        });

        BlogPost::query()->each(function (BlogPost $post) use ($photoMap, &$updated) {
            $changes = [];

            if ($newUrl = $this->resolveUrl($post->author_image_url, $photoMap)) {
                if ($newUrl !== $post->author_image_url) {
                    $changes['author_image_url'] = $newUrl;
                }
            }

            if ($newUrl = $this->resolveUrl($post->featured_image_url, $photoMap)) {
                if ($newUrl !== $post->featured_image_url) {
                    $changes['featured_image_url'] = $newUrl;
                }
            }

            if ($changes !== []) {
                $post->update($changes);
                $updated++;
            }
        });

        TeamMember::query()->each(function (TeamMember $member) use ($photoMap, &$updated) {
            $newUrl = $this->resolveUrl($member->image_url, $photoMap);

            if ($newUrl && $newUrl !== $member->image_url) {
                $member->update(['image_url' => $newUrl]);
                $updated++;
            }
        });

        PageSection::query()->each(function (PageSection $section) use ($photoMap, &$updated) {
            $settings = $section->settings;

            if (! is_array($settings) || ! isset($settings['image'])) {
                return;
            }

            $newUrl = $this->resolveUrl($settings['image'], $photoMap);

            if ($newUrl && $newUrl !== $settings['image']) {
                $settings['image'] = $newUrl;
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
            return $existing->url;
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

            return $existing->fresh()->url;
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

        return $asset->url;
    }

    /**
     * @param  array<string, string>  $urls
     * @return array<string, string>
     */
    private function buildPhotoIdMap(array $urls): array
    {
        $map = [];

        foreach ($this->definitions() as $key => $definition) {
            $photoId = $this->extractPhotoId($definition['source']);

            if ($photoId) {
                $map[$photoId] = $urls[$key];
            }

            foreach ($definition['legacy_photo_ids'] ?? [] as $legacyId) {
                $map[$legacyId] = $urls[$key];
            }
        }

        return $map;
    }

    /**
     * @param  array<string, string>  $photoMap
     */
    private function resolveUrl(?string $url, array $photoMap): ?string
    {
        if (! $url) {
            return null;
        }

        $photoId = $this->extractPhotoId($url);

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
