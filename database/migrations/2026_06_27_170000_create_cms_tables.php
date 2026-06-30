<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('group')->default('general');
            $table->json('value')->nullable();
            $table->timestamps();
        });

        Schema::create('setting_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('setting_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5)->default('en');
            $table->text('value')->nullable();
            $table->timestamps();

            $table->unique(['setting_id', 'locale']);
        });

        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('menu_items')->cascadeOnDelete();
            $table->string('url');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('menu_item_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_item_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5)->default('en');
            $table->string('label');
            $table->timestamps();

            $table->unique(['menu_item_id', 'locale']);
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('page_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5)->default('en');
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('badge')->nullable();
            $table->timestamps();

            $table->unique(['page_id', 'locale']);
        });

        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->unsignedInteger('sort_order')->default(0);
            $table->json('settings')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('page_section_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_section_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5)->default('en');
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->longText('content')->nullable();
            $table->string('cta_label')->nullable();
            $table->string('cta_url')->nullable();
            $table->string('badge')->nullable();
            $table->timestamps();

            $table->unique(['page_section_id', 'locale']);
        });

        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('image_url');
            $table->string('gradient')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('hero_slide_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hero_slide_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5)->default('en');
            $table->string('badge')->nullable();
            $table->string('title')->nullable();
            $table->string('title_highlight')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('cta_primary_label')->nullable();
            $table->string('cta_primary_url')->nullable();
            $table->string('cta_secondary_label')->nullable();
            $table->string('cta_secondary_url')->nullable();
            $table->timestamps();

            $table->unique(['hero_slide_id', 'locale']);
        });

        Schema::create('stats', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->nullable();
            $table->integer('numeric_value')->default(0);
            $table->string('suffix')->nullable();
            $table->string('color')->nullable();
            $table->string('bg_gradient')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('context')->default('home');
            $table->timestamps();
        });

        Schema::create('stat_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stat_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5)->default('en');
            $table->string('label');
            $table->string('sublabel')->nullable();
            $table->timestamps();

            $table->unique(['stat_id', 'locale']);
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        Schema::create('service_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5)->default('en');
            $table->string('title');
            $table->text('short_desc')->nullable();
            $table->longText('full_desc')->nullable();
            $table->string('price')->nullable();
            $table->timestamps();

            $table->unique(['service_id', 'locale']);
        });

        Schema::create('process_steps', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('step_number')->default(1);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('process_step_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('process_step_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5)->default('en');
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['process_step_id', 'locale']);
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('avatar_url')->nullable();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('type')->default('client');
            $table->timestamps();
        });

        Schema::create('testimonial_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('testimonial_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5)->default('en');
            $table->string('name');
            $table->string('role')->nullable();
            $table->text('text');
            $table->timestamps();

            $table->unique(['testimonial_id', 'locale']);
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('type');
            $table->string('icon')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['slug', 'type']);
        });

        Schema::create('category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5)->default('en');
            $table->string('name');
            $table->string('label')->nullable();
            $table->timestamps();

            $table->unique(['category_id', 'locale']);
        });

        Schema::create('portfolio_items', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('client_name')->nullable();
            $table->string('image_url')->nullable();
            $table->string('duration')->nullable();
            $table->string('budget')->nullable();
            $table->string('results')->nullable();
            $table->string('metric')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('grid_size')->default('small');
            $table->timestamps();
        });

        Schema::create('portfolio_item_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_item_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5)->default('en');
            $table->string('title');
            $table->text('results_text')->nullable();
            $table->string('metric')->nullable();
            $table->timestamps();

            $table->unique(['portfolio_item_id', 'locale']);
        });

        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('author_name')->nullable();
            $table->string('author_image_url')->nullable();
            $table->string('featured_image_url')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->unsignedSmallInteger('read_time_minutes')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('blog_post_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_post_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5)->default('en');
            $table->string('title');
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();

            $table->unique(['blog_post_id', 'locale']);
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('faq_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faq_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5)->default('en');
            $table->string('question');
            $table->text('answer');
            $table->timestamps();

            $table->unique(['faq_id', 'locale']);
        });

        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('image_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('team_member_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_member_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5)->default('en');
            $table->string('name');
            $table->string('role')->nullable();
            $table->text('bio')->nullable();
            $table->timestamps();

            $table->unique(['team_member_id', 'locale']);
        });

        Schema::create('timeline_events', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            $table->string('icon')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('timeline_event_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('timeline_event_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5)->default('en');
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['timeline_event_id', 'locale']);
        });

        Schema::create('skill_bars', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('percent')->default(0);
            $table->string('color')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('skill_bar_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skill_bar_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5)->default('en');
            $table->string('label');
            $table->timestamps();

            $table->unique(['skill_bar_id', 'locale']);
        });

        Schema::create('value_items', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('value_item_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('value_item_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5)->default('en');
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['value_item_id', 'locale']);
        });

        Schema::create('feature_highlights', function (Blueprint $table) {
            $table->id();
            $table->string('context')->default('services');
            $table->string('icon')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('feature_highlight_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feature_highlight_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5)->default('en');
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['feature_highlight_id', 'locale'], 'fh_trans_locale_uniq');
        });

        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->string('flag')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('timezone')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_headquarters')->default(false);
            $table->timestamps();
        });

        Schema::create('office_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('office_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5)->default('en');
            $table->string('title');
            $table->string('status')->nullable();
            $table->timestamps();

            $table->unique(['office_id', 'locale']);
        });

        Schema::create('partner_labels', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('partner_label_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_label_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5)->default('en');
            $table->string('label');
            $table->timestamps();

            $table->unique(['partner_label_id', 'locale']);
        });

        Schema::create('client_logos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('client_logo_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_logo_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5)->default('en');
            $table->string('display_name');
            $table->timestamps();

            $table->unique(['client_logo_id', 'locale']);
        });

        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('file_url')->nullable();
            $table->timestamps();
        });

        Schema::create('resource_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5)->default('en');
            $table->string('title');
            $table->string('type_label')->nullable();
            $table->timestamps();

            $table->unique(['resource_id', 'locale']);
        });

        Schema::create('awards', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('award_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('award_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5)->default('en');
            $table->string('title');
            $table->string('organization')->nullable();
            $table->string('year_label')->nullable();
            $table->timestamps();

            $table->unique(['award_id', 'locale']);
        });

        Schema::create('media_assets', function (Blueprint $table) {
            $table->id();
            $table->string('disk')->default('public');
            $table->string('path');
            $table->string('filename');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->string('alt_text')->nullable();
            $table->string('folder')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('seo_meta', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('seoable');
            $table->string('page_slug')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('robots')->nullable();
            $table->json('structured_data')->nullable();
            $table->timestamps();
        });

        Schema::create('seo_meta_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seo_meta_id')->constrained('seo_meta')->cascadeOnDelete();
            $table->string('locale', 5)->default('en');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image_url')->nullable();
            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image_url')->nullable();
            $table->timestamps();

            $table->unique(['seo_meta_id', 'locale']);
        });

        Schema::create('contact_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference_id')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('service')->nullable();
            $table->string('budget')->nullable();
            $table->text('message');
            $table->string('locale', 5)->default('en');
            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('status')->default('new');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('reference_id')->unique();
            $table->string('type');
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->string('service_text')->nullable();
            $table->text('message')->nullable();
            $table->string('budget')->nullable();
            $table->string('source')->nullable();
            $table->string('locale', 5)->default('en');
            $table->string('status')->default('new');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('newsletter_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('locale', 5)->default('en');
            $table->boolean('is_active')->default(true);
            $table->string('token')->unique();
            $table->timestamp('subscribed_at')->useCurrent();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_subscriptions');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('contact_requests');
        Schema::dropIfExists('seo_meta_translations');
        Schema::dropIfExists('seo_meta');
        Schema::dropIfExists('media_assets');
        Schema::dropIfExists('award_translations');
        Schema::dropIfExists('awards');
        Schema::dropIfExists('resource_translations');
        Schema::dropIfExists('resources');
        Schema::dropIfExists('client_logo_translations');
        Schema::dropIfExists('client_logos');
        Schema::dropIfExists('partner_label_translations');
        Schema::dropIfExists('partner_labels');
        Schema::dropIfExists('office_translations');
        Schema::dropIfExists('offices');
        Schema::dropIfExists('feature_highlight_translations');
        Schema::dropIfExists('feature_highlights');
        Schema::dropIfExists('value_item_translations');
        Schema::dropIfExists('value_items');
        Schema::dropIfExists('skill_bar_translations');
        Schema::dropIfExists('skill_bars');
        Schema::dropIfExists('timeline_event_translations');
        Schema::dropIfExists('timeline_events');
        Schema::dropIfExists('team_member_translations');
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('faq_translations');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('blog_post_translations');
        Schema::dropIfExists('blog_posts');
        Schema::dropIfExists('portfolio_item_translations');
        Schema::dropIfExists('portfolio_items');
        Schema::dropIfExists('category_translations');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('testimonial_translations');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('process_step_translations');
        Schema::dropIfExists('process_steps');
        Schema::dropIfExists('service_translations');
        Schema::dropIfExists('services');
        Schema::dropIfExists('stat_translations');
        Schema::dropIfExists('stats');
        Schema::dropIfExists('hero_slide_translations');
        Schema::dropIfExists('hero_slides');
        Schema::dropIfExists('page_section_translations');
        Schema::dropIfExists('page_sections');
        Schema::dropIfExists('page_translations');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('menu_item_translations');
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('setting_translations');
        Schema::dropIfExists('settings');
    }
};
