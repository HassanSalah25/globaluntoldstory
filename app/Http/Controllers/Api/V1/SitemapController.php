<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Services\Seo\SitemapService;
use Illuminate\Http\Response;

class SitemapController extends ApiController
{
    public function __construct(
        private readonly SitemapService $sitemap,
    ) {}

    public function index(): Response
    {
        return response($this->sitemap->generate(), 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
}
