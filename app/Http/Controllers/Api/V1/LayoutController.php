<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Services\Content\LayoutService;
use Illuminate\Http\JsonResponse;

class LayoutController extends ApiController
{
    public function __construct(
        private readonly LayoutService $layout,
    ) {}

    public function index(): JsonResponse
    {
        $locale = app()->getLocale();

        return $this->success($this->layout->getLayout($locale), $locale);
    }
}
