<?php

namespace App\Services\Seo;

use App\Models\BlogPost;
use App\Models\Page;
use App\Models\PortfolioItem;
use App\Models\Service;

class SitemapService
{
    public function generate(): string
    {
        $baseUrl = rtrim(config('app.url'), '/');
        $urls = [];

        $staticPages = Page::query()->where('is_active', true)->pluck('slug');
        foreach ($staticPages as $slug) {
            if ($slug === 'home') {
                $urls[] = $this->urlEntry("{$baseUrl}/", now(), 'weekly', '1.0');
            } else {
                $urls[] = $this->urlEntry("{$baseUrl}/{$slug}", now(), 'monthly', '0.8');
            }
        }

        Service::query()->where('is_active', true)->pluck('slug')->each(function ($slug) use ($baseUrl, &$urls) {
            $urls[] = $this->urlEntry("{$baseUrl}/services#{$slug}", now(), 'monthly', '0.7');
        });

        PortfolioItem::query()->where('is_active', true)->get(['slug', 'updated_at'])->each(function ($item) use ($baseUrl, &$urls) {
            $urls[] = $this->urlEntry("{$baseUrl}/portfolio/{$item->slug}", $item->updated_at, 'monthly', '0.7');
        });

        BlogPost::query()->where('is_published', true)->get(['slug', 'updated_at', 'published_at'])->each(function ($post) use ($baseUrl, &$urls) {
            $lastMod = $post->updated_at ?? $post->published_at ?? now();
            $urls[] = $this->urlEntry("{$baseUrl}/blog/{$post->slug}", $lastMod, 'weekly', '0.6');
        });

        $body = implode("\n", $urls);

        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
{$body}
</urlset>
XML;
    }

    private function urlEntry(string $loc, $lastmod, string $changefreq, string $priority): string
    {
        $lastmodFormatted = $lastmod instanceof \DateTimeInterface
            ? $lastmod->format('Y-m-d')
            : now()->format('Y-m-d');

        return "  <url>\n    <loc>{$loc}</loc>\n    <lastmod>{$lastmodFormatted}</lastmod>\n    <changefreq>{$changefreq}</changefreq>\n    <priority>{$priority}</priority>\n  </url>";
    }
}
