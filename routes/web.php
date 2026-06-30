<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {

    // Guest-only routes
    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login'])->name('login.post');
        Route::get('forgot-password', [AdminAuthController::class, 'showForgotPassword'])->name('password.request');
        Route::post('forgot-password', [AdminAuthController::class, 'sendResetLink'])->name('password.email');
        Route::get('reset-password/{token}', [AdminAuthController::class, 'showResetPassword'])->name('password.reset');
        Route::post('reset-password', [AdminAuthController::class, 'resetPassword'])->name('password.update');
    });

    // Authenticated admin routes
    Route::middleware('admin.access')->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Profile
        Route::get('profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');

        // Hero Slides
        Route::resource('hero-slides', Admin\HeroSlideController::class)->names('hero-slides');
        Route::patch('hero-slides/{hero_slide}/toggle', [Admin\HeroSlideController::class, 'toggle'])->name('hero-slides.toggle');

        // Services
        Route::resource('services', Admin\ServiceController::class)->names('services');
        Route::patch('services/{service}/toggle', [Admin\ServiceController::class, 'toggle'])->name('services.toggle');

        // Portfolio
        Route::resource('portfolio', Admin\PortfolioItemController::class)->names('portfolio');
        Route::patch('portfolio/{portfolio_item}/toggle', [Admin\PortfolioItemController::class, 'toggle'])->name('portfolio.toggle');

        // Blog
        Route::resource('blog', Admin\BlogPostController::class)->names('blog');
        Route::patch('blog/{blog_post}/toggle', [Admin\BlogPostController::class, 'toggle'])->name('blog.toggle');

        // FAQs
        Route::resource('faqs', Admin\FaqController::class)->names('faqs');
        Route::patch('faqs/{faq}/toggle', [Admin\FaqController::class, 'toggle'])->name('faqs.toggle');

        // Team
        Route::resource('team', Admin\TeamMemberController::class)->names('team');
        Route::patch('team/{team_member}/toggle', [Admin\TeamMemberController::class, 'toggle'])->name('team.toggle');

        // Testimonials
        Route::resource('testimonials', Admin\TestimonialController::class)->names('testimonials');
        Route::patch('testimonials/{testimonial}/toggle', [Admin\TestimonialController::class, 'toggle'])->name('testimonials.toggle');

        // Process Steps
        Route::resource('process-steps', Admin\ProcessStepController::class)->names('process-steps');

        // Stats
        Route::resource('stats', Admin\StatController::class)->names('stats');

        // Timeline
        Route::resource('timeline', Admin\TimelineEventController::class)->names('timeline');

        // Skill Bars
        Route::resource('skill-bars', Admin\SkillBarController::class)->names('skill-bars');

        // Value Items
        Route::resource('value-items', Admin\ValueItemController::class)->names('value-items');

        // Feature Highlights
        Route::resource('feature-highlights', Admin\FeatureHighlightController::class)->names('feature-highlights');

        // Awards
        Route::resource('awards', Admin\AwardController::class)->names('awards');

        // Client Logos
        Route::resource('client-logos', Admin\ClientLogoController::class)->names('client-logos');
        Route::patch('client-logos/{client_logo}/toggle', [Admin\ClientLogoController::class, 'toggle'])->name('client-logos.toggle');

        // Offices
        Route::resource('offices', Admin\OfficeController::class)->names('offices');

        // Menus
        Route::resource('menus', Admin\MenuController::class)->names('menus');

        // Pages
        Route::resource('pages', Admin\PageController::class)->names('pages');
        Route::patch('pages/{page}/toggle', [Admin\PageController::class, 'toggle'])->name('pages.toggle');

        // Settings
        Route::resource('settings', Admin\SettingController::class)->only(['index', 'edit', 'update'])->names('settings');

        // Categories
        Route::resource('categories', Admin\CategoryController::class)->names('categories');

        // Media Library
        Route::get('media', [Admin\MediaController::class, 'index'])->name('media.index');
        Route::post('media', [Admin\MediaController::class, 'store'])->name('media.store');
        Route::delete('media/{media_asset}', [Admin\MediaController::class, 'destroy'])->name('media.destroy');
        Route::patch('media/{media_asset}/alt', [Admin\MediaController::class, 'updateAlt'])->name('media.update-alt');

        // SEO
        Route::get('seo', [Admin\SeoMetaController::class, 'index'])->name('seo.index');
        Route::post('seo/create-for-page', [Admin\SeoMetaController::class, 'createForPage'])->name('seo.createForPage');
        Route::get('seo/{seo_meta}/edit', [Admin\SeoMetaController::class, 'edit'])->name('seo.edit');
        Route::put('seo/{seo_meta}', [Admin\SeoMetaController::class, 'update'])->name('seo.update');

        // Resource Items
        Route::resource('resource-items', Admin\ResourceItemController::class)->names('resource-items');

        // Contact Requests
        Route::resource('contact-requests', Admin\ContactRequestWebController::class)
            ->only(['index', 'show', 'destroy'])
            ->names('contact-requests');
        Route::patch('contact-requests/{contact_request}/status', [Admin\ContactRequestWebController::class, 'updateStatus'])->name('contact-requests.status');

        // Leads
        Route::resource('leads', Admin\LeadWebController::class)
            ->only(['index', 'show', 'destroy'])
            ->names('leads');
        Route::patch('leads/{lead}/status', [Admin\LeadWebController::class, 'updateStatus'])->name('leads.status');

        // Newsletter
        Route::get('newsletter', [Admin\NewsletterController::class, 'index'])->name('newsletter.index');
        Route::patch('newsletter/{newsletter_subscription}/toggle', [Admin\NewsletterController::class, 'toggle'])->name('newsletter.toggle');
        Route::get('newsletter/export', [Admin\NewsletterController::class, 'export'])->name('newsletter.export');
    });
});
