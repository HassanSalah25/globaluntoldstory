<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Services\Content\PortfolioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PortfolioController extends ApiController
{
    public function __construct(
        private readonly PortfolioService $portfolio,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $locale = app()->getLocale();

        return $this->success(
            $this->portfolio->list(
                $locale,
                category: $request->query('category'),
                perPage: (int) $request->query('per_page', 12),
                page: (int) $request->query('page', 1),
            ),
            $locale
        );
    }

    public function show(string $slug): JsonResponse
    {
        $locale = app()->getLocale();

        return $this->success($this->portfolio->getBySlug($slug, $locale), $locale);
    }
}
