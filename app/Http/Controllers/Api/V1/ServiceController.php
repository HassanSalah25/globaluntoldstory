<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Services\Content\ServiceCatalogService;
use Illuminate\Http\JsonResponse;

class ServiceController extends ApiController
{
    public function __construct(
        private readonly ServiceCatalogService $services,
    ) {}

    public function index(): JsonResponse
    {
        $locale = app()->getLocale();

        return $this->success([
            'items' => $this->services->listAll($locale),
        ], $locale);
    }

    public function show(string $slug): JsonResponse
    {
        $locale = app()->getLocale();

        return $this->success($this->services->getBySlug($slug, $locale), $locale);
    }
}
