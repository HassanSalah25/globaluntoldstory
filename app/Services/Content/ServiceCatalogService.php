<?php

namespace App\Services\Content;

use App\Models\Service;

class ServiceCatalogService
{
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
            ->map(fn ($service) => $this->mapService($service, $locale))
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

    private function mapService(Service $service, string $locale, bool $detailed = false): array
    {
        $t = $service->translate($locale);

        $data = [
            'id' => $service->slug,
            'slug' => $service->slug,
            'icon' => $service->icon,
            'title' => $t?->title,
            'shortDesc' => $t?->short_desc,
            'price' => $t?->price ?? '',
            'fullDesc' => $t?->full_desc ?? $t?->short_desc,
            'features' => [],
            'isFeatured' => $service->is_featured,
        ];

        if ($detailed) {
            $data['sortOrder'] = $service->sort_order;
        }

        return $data;
    }
}
