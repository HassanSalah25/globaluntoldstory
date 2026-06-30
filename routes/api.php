<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\ContactRequestController as AdminContactRequestController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LeadController as AdminLeadController;
use App\Http\Controllers\Api\V1\AboutController;
use App\Http\Controllers\Api\V1\BlogController;
use App\Http\Controllers\Api\V1\ContactController;
use App\Http\Controllers\Api\V1\FaqController;
use App\Http\Controllers\Api\V1\HomeController;
use App\Http\Controllers\Api\V1\LayoutController;
use App\Http\Controllers\Api\V1\LeadController;
use App\Http\Controllers\Api\V1\NewsletterController;
use App\Http\Controllers\Api\V1\PageController;
use App\Http\Controllers\Api\V1\PortfolioController;
use App\Http\Controllers\Api\V1\SeoController;
use App\Http\Controllers\Api\V1\ServiceController;
use App\Http\Controllers\Api\V1\SitemapController;
use App\Http\Controllers\Api\V1\TestimonialController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('home', [HomeController::class, 'index']);
    Route::get('layout', [LayoutController::class, 'index']);
    Route::get('pages/{slug}', [PageController::class, 'show']);
    Route::get('services', [ServiceController::class, 'index']);
    Route::get('services/{slug}', [ServiceController::class, 'show']);
    Route::get('portfolio', [PortfolioController::class, 'index']);
    Route::get('portfolio/{slug}', [PortfolioController::class, 'show']);
    Route::get('blog', [BlogController::class, 'index']);
    Route::get('blog/{slug}', [BlogController::class, 'show']);
    Route::get('about', [AboutController::class, 'index']);
    Route::get('testimonials', [TestimonialController::class, 'index']);
    Route::get('faqs', [FaqController::class, 'index']);
    Route::get('seo/{type}/{slug?}', [SeoController::class, 'show']);
    Route::get('sitemap.xml', [SitemapController::class, 'index']);

    Route::post('contact', [ContactController::class, 'store']);
    Route::post('leads/quote', [LeadController::class, 'storeQuote']);
    Route::post('newsletter/subscribe', [NewsletterController::class, 'subscribe']);
    Route::post('newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe']);

    Route::prefix('admin')->group(function () {
        Route::post('login', [AdminAuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AdminAuthController::class, 'logout']);
            Route::get('me', [AdminAuthController::class, 'me']);
            Route::get('dashboard', [DashboardController::class, 'index']);

            Route::get('contact-requests', [AdminContactRequestController::class, 'index']);
            Route::get('contact-requests/{contact_request}', [AdminContactRequestController::class, 'show']);
            Route::patch('contact-requests/{contact_request}', [AdminContactRequestController::class, 'update']);
            Route::put('contact-requests/{contact_request}', [AdminContactRequestController::class, 'update']);

            Route::get('leads', [AdminLeadController::class, 'index']);
            Route::get('leads/{lead}', [AdminLeadController::class, 'show']);
            Route::patch('leads/{lead}', [AdminLeadController::class, 'update']);
            Route::put('leads/{lead}', [AdminLeadController::class, 'update']);
        });
    });
});
