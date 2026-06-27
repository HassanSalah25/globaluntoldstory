<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Services\Content\HomePageService;
use Illuminate\Http\JsonResponse;

class HomeController extends ApiController
{
    public function __construct(
        private readonly HomePageService $homePage,
    ) {}

    public function index(): JsonResponse
    {
        $locale = app()->getLocale();

        return $this->success($this->homePage->getHome($locale), $locale);
    }
}
