<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Services\Content\BlogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogController extends ApiController
{
    public function __construct(
        private readonly BlogService $blog,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $locale = app()->getLocale();

        return $this->success(
            $this->blog->list(
                $locale,
                category: $request->query('category'),
                tag: $request->query('tag'),
                search: $request->query('search'),
                perPage: (int) $request->query('per_page', 12),
                page: (int) $request->query('page', 1),
            ),
            $locale
        );
    }

    public function show(string $slug): JsonResponse
    {
        $locale = app()->getLocale();

        return $this->success($this->blog->getBySlug($slug, $locale), $locale);
    }
}
