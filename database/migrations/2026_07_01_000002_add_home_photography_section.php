<?php

use App\Models\Page;
use App\Models\PageSection;
use App\Support\MediaUrl;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $home = Page::query()->where('slug', 'home')->first();

        if (! $home) {
            return;
        }

        $exists = PageSection::query()
            ->where('page_id', $home->id)
            ->where('type', 'photography')
            ->exists();

        if ($exists) {
            return;
        }

        $section = PageSection::query()->create([
            'page_id' => $home->id,
            'type' => 'photography',
            'sort_order' => 3,
            'settings' => [
                'image' => MediaUrl::toStoragePath('frontend/professional-cinema-camera-equipment-egypt-arri-alexa-mini-lf-film-production-equipment.webp')
                    ?? 'frontend/professional-cinema-camera-equipment-egypt-arri-alexa-mini-lf-film-production-equipment.webp',
            ],
            'is_active' => true,
        ]);

        $section->translations()->create([
            'locale' => 'en',
            'badge' => 'Photography',
            'title' => 'Professional Cinema Camera Equipment',
            'content' => 'Professional ARRI Alexa Mini LF cinema camera and filmmaking equipment used for commercial productions, documentaries, branded content, and film projects in Egypt.',
            'subtitle' => 'Polished and aligned with your tone',
            'cta_label' => 'Contact Us',
            'cta_url' => '/contact',
        ]);

        $section->translations()->create([
            'locale' => 'ar',
            'badge' => 'التصوير',
            'title' => 'معدات الكاميرات السينمائية الاحترافية',
            'content' => 'كاميرا ARRI Alexa Mini LF السينمائية ومعدات التصوير السينمائي المستخدمة في الإنتاجات التجارية والأفلام الوثائقية والمحتوى المرتبط بالعلامات ومشاريع الأفلام في مصر.',
            'subtitle' => 'مصقول ومتناسق مع أسلوبك',
            'cta_label' => 'تواصل معنا',
            'cta_url' => '/contact',
        ]);
    }

    public function down(): void
    {
        $home = Page::query()->where('slug', 'home')->first();

        if (! $home) {
            return;
        }

        PageSection::query()
            ->where('page_id', $home->id)
            ->where('type', 'photography')
            ->delete();
    }
};
