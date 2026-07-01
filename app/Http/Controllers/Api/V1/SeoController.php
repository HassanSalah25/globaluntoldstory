<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Models\BlogPost;
use App\Models\Service;
use App\Services\Seo\SeoService;
use Illuminate\Http\JsonResponse;

class SeoController extends ApiController
{
    public function __construct(
        private readonly SeoService $seo,
    ) {}

    public function show(string $type, ?string $slug = null): JsonResponse
    {
        $locale = app()->getLocale();

        $data = match ($type) {
            'page' => $this->seo->getForPageSlug($slug ?? 'home', $locale),
            'blog' => $slug
                ? $this->seo->getForBlogPost(
                    BlogPost::query()->where('slug', $slug)->firstOrFail(),
                    $locale
                )
                : null,
            'service' => $slug
                ? $this->seo->getForService(
                    Service::query()->where('slug', $slug)->firstOrFail(),
                    $locale
                )
                : null,
            default => null,
        };

        if ($data === null) {
            return $this->error('SEO data not found.', 404);
        }

        return $this->success($data, $locale);
    }
}
