<?php

namespace App\Services\Content;

use App\Models\Service;
use App\Services\Media\FrontendMediaImporter;
use App\Support\MediaUrl;

class ServiceCatalogService
{
    private const FEATURED_IMAGE_KEYS = [
        'on-ground-egypt' => 'svc-on-ground',
        'commercial' => 'svc-commercial',
        'documentary' => 'svc-documentary',
        'corporate' => 'svc-corporate',
    ];

    private const FEATURED_COPY = [
        'on-ground-egypt' => [
            'en' => [
                'title' => 'On-Ground Production Services in Egypt',
                'short_desc' => 'On-ground production support in Egypt including permits, logistics, crew coordination, location management, and filming assistance.',
            ],
            'ar' => [
                'title' => 'خدمات الإنتاج الميداني في مصر',
                'short_desc' => 'دعم إنتاج ميداني في مصر يشمل التصاريح واللوجستيات وتنسيق الطاقم وإدارة المواقع والمساعدة في التصوير.',
            ],
        ],
        'commercial' => [
            'en' => [
                'title' => 'Commercial Food Advertising Production',
                'short_desc' => 'Commercial advertising production for a food and beverage brand, showcasing professional storytelling and branded content creation.',
            ],
            'ar' => [
                'title' => 'إنتاج إعلانات الأطعمة والمشروبات',
                'short_desc' => 'إنتاج إعلانات تجارية لعلامات الأطعمة والمشروبات مع سرد احترافي ومحتوى مرتبط بالعلامة.',
            ],
        ],
        'documentary' => [
            'en' => [
                'title' => 'Documentary Production in Egypt',
                'short_desc' => 'Professional documentary production services in Egypt, including filming, logistics, and on-location support at iconic heritage sites.',
            ],
            'ar' => [
                'title' => 'إنتاج الأفلام الوثائقية في مصر',
                'short_desc' => 'خدمات إنتاج وثائقي احترافية في مصر تشمل التصوير واللوجستيات والدعم الميداني في مواقع تراثية أيقونية.',
            ],
        ],
        'corporate' => [
            'en' => [
                'title' => 'Corporate and Industrial Content Production',
                'short_desc' => 'Corporate and industrial video production services for energy, infrastructure, manufacturing, and engineering sectors.',
            ],
            'ar' => [
                'title' => 'إنتاج المحتوى المؤسسي والصناعي',
                'short_desc' => 'خدمات إنتاج فيديو مؤسسي وصناعي لقطاعات الطاقة والبنية التحتية والتصنيع والهندسة.',
            ],
        ],
    ];

    public function listAll(string $locale): array
    {
        return Service::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with('translations')
            ->get()
            ->map(fn ($service) => $this->mapService($service, $locale))
            ->values()
            ->all();
    }

    public function listFeatured(string $locale, int $limit = 4): array
    {
        return Service::query()
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->with('translations')
            ->limit($limit)
            ->get()
            ->map(fn ($service) => $this->mapService($service, $locale, featuredImage: true))
            ->values()
            ->all();
    }

    public function getBySlug(string $slug, string $locale): array
    {
        $service = Service::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with('translations')
            ->firstOrFail();

        return $this->mapService($service, $locale, detailed: true);
    }

    private function mapService(Service $service, string $locale, bool $detailed = false, bool $featuredImage = false): array
    {
        $t = $service->translate($locale);
        $featuredCopy = $featuredImage ? (self::FEATURED_COPY[$service->slug][$locale] ?? null) : null;

        $title = $featuredCopy['title'] ?? $t?->title;
        $shortDesc = $featuredCopy['short_desc'] ?? $t?->short_desc;

        $data = [
            'id' => $service->slug,
            'slug' => $service->slug,
            'icon' => $service->icon,
            'imageUrl' => $this->resolveImageUrl($service, $featuredImage),
            'title' => $title,
            'shortDesc' => $shortDesc,
            'price' => $t?->price ?? '',
            'fullDesc' => $featuredCopy['short_desc'] ?? $t?->full_desc ?? $t?->short_desc,
            'features' => [],
            'isFeatured' => $service->is_featured,
        ];

        if ($detailed) {
            $data['sortOrder'] = $service->sort_order;
        }

        return $data;
    }

    private function resolveImageUrl(Service $service, bool $featuredImage = false): ?string
    {
        $path = $service->image_url;

        if ($featuredImage && isset(self::FEATURED_IMAGE_KEYS[$service->slug])) {
            $path = FrontendMediaImporter::resolvedPath(self::FEATURED_IMAGE_KEYS[$service->slug]);
        }

        if (! $path) {
            return null;
        }

        return MediaUrl::toPublicUrl($path) ?? asset('storage/'.$path);
    }
}
