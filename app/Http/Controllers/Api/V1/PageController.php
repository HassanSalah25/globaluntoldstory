<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Services\Content\PageService;
use Illuminate\Http\JsonResponse;

class PageController extends ApiController
{
    public function __construct(
        private readonly PageService $pages,
    ) {}

    public function show(string $slug): JsonResponse
    {
        $locale = app()->getLocale();

        return $this->success($this->pages->getPage($slug, $locale), $locale);
    }
}
