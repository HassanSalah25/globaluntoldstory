<?php

namespace App\Services\Seo;

use App\Models\BlogPost;
use App\Models\Page;
use App\Models\SeoMeta;
use App\Models\Service;

class SeoService
{
    public function getForPageSlug(string $slug, string $locale): ?array
    {
        $seo = SeoMeta::query()
            ->where('page_slug', $slug)
            ->with('translations')
            ->first();

        if (! $seo) {
            $page = Page::query()->where('slug', $slug)->with('translations')->first();
            if (! $page) {
                return null;
            }

            $translation = $page->translate($locale);

            return [
                'metaTitle' => $translation?->title,
                'metaDescription' => $translation?->subtitle,
                'ogTitle' => $translation?->title,
                'ogDescription' => $translation?->subtitle,
            ];
        }

        return $this->mapSeo($seo, $locale);
    }

    public function getForService(Service $service, string $locale): array
    {
        $seo = SeoMeta::query()
            ->where('seoable_type', Service::class)
            ->where('seoable_id', $service->id)
            ->with('translations')
            ->first();

        if ($seo) {
            return $this->mapSeo($seo, $locale) ?? [];
        }

        $translation = $service->translate($locale);

        return [
            'metaTitle' => $translation?->title,
            'metaDescription' => $translation?->short_desc,
            'ogTitle' => $translation?->title,
            'ogDescription' => $translation?->short_desc,
            'ogImageUrl' => $service->image_url,
        ];
    }

    public function getForBlogPost(BlogPost $post, string $locale): array
    {
        $seo = SeoMeta::query()
            ->where('seoable_type', BlogPost::class)
            ->where('seoable_id', $post->id)
            ->with('translations')
            ->first();

        if ($seo) {
            return $this->mapSeo($seo, $locale) ?? [];
        }

        $translation = $post->translate($locale);

        return [
            'metaTitle' => $translation?->title,
            'metaDescription' => $translation?->excerpt,
            'ogTitle' => $translation?->title,
            'ogDescription' => $translation?->excerpt,
            'ogImageUrl' => $post->featured_image_url,
        ];
    }

    private function mapSeo(SeoMeta $seo, string $locale): ?array
    {
        $t = $seo->translate($locale);

        if (! $t) {
            return null;
        }

        return [
            'metaTitle' => $t->meta_title,
            'metaDescription' => $t->meta_description,
            'ogTitle' => $t->og_title,
            'ogDescription' => $t->og_description,
            'ogImageUrl' => $t->og_image_url,
            'twitterTitle' => $t->twitter_title,
            'twitterDescription' => $t->twitter_description,
            'twitterImageUrl' => $t->twitter_image_url,
            'canonicalUrl' => $seo->canonical_url,
            'robots' => $seo->robots,
            'structuredData' => $seo->structured_data,
        ];
    }
}
