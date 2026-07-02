<?php

namespace App\Services\Content;

use App\Models\Category;
use App\Models\FeatureHighlight;
use App\Models\Office;
use App\Models\Page;
use App\Models\PartnerLabel;
use App\Models\Resource;
use App\Models\SkillBar;
use App\Models\Stat;
use App\Models\TeamMember;
use App\Models\TimelineEvent;
use App\Models\ValueItem;
use App\Services\Media\FrontendMediaImporter;
use App\Services\Seo\SeoService;
use App\Services\Settings\SettingService;
use App\Support\MediaUrl;
use Illuminate\Support\Str;

class PageService
{
    public function __construct(
        private readonly SeoService $seo,
        private readonly SettingService $settings,
        private readonly ServiceCatalogService $services,
        private readonly PortfolioService $portfolio,
        private readonly BlogService $blog,
    ) {}

    public function getPage(string $slug, string $locale): array
    {
        $page = Page::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with(['translations', 'sections.translations'])
            ->firstOrFail();

        $translation = $page->translate($locale);

        $base = [
            'slug' => $page->slug,
            'badge' => $translation?->badge,
            'title' => $translation?->title,
            'subtitle' => $translation?->subtitle,
            'seo' => $this->seo->getForPageSlug($slug, $locale),
        ];

        return match ($slug) {
            'about' => array_merge($base, $this->getAboutSections($page, $locale)),
            'services' => array_merge($base, $this->getServicesSections($locale)),
            'portfolio' => array_merge($base, $this->getPortfolioSections($locale)),
            'blog' => array_merge($base, $this->getBlogSections($locale)),
            'contact' => array_merge($base, $this->getContactSections($locale)),
            default => $base,
        };
    }

    private function getAboutSections(Page $page, string $locale): array
    {
        $sections = $page->sections->keyBy('type');
        $story = $sections->get('story')?->translate($locale);
        $mission = $sections->get('mission')?->translate($locale);
        $vision = $sections->get('vision')?->translate($locale);
        $storySection = $sections->get('story');

        $storyParagraphs = $story?->content
            ? array_values(array_filter(explode("\n\n", $story->content)))
            : [];

        $stats = Stat::query()
            ->where('context', 'home')
            ->orderBy('sort_order')
            ->with('translations')
            ->get()
            ->map(fn ($stat) => [
                'value' => (string) $stat->numeric_value.($stat->suffix ?? ''),
                'label' => $stat->translate($locale)?->label,
                'icon' => $stat->icon,
            ])->values()->all();

        $heroStats = array_slice($stats, 0, 3);

        $team = TeamMember::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with('translations')
            ->get()
            ->map(fn ($member) => [
                'name' => $member->translate($locale)?->name,
                'role' => $member->translate($locale)?->role,
                'image' => $member->image_url,
                'bio' => $member->translate($locale)?->bio,
            ])->values()->all();

        $timeline = TimelineEvent::query()
            ->orderBy('sort_order')
            ->with('translations')
            ->get()
            ->map(fn ($event) => [
                'year' => $event->year,
                'icon' => $event->icon,
                'title' => $event->translate($locale)?->title,
                'description' => $event->translate($locale)?->description,
            ])->values()->all();

        $skills = SkillBar::query()
            ->orderBy('sort_order')
            ->with('translations')
            ->get()
            ->map(fn ($skill) => [
                'label' => $skill->translate($locale)?->label,
                'percent' => $skill->percent,
                'color' => $skill->color,
            ])->values()->all();

        $values = ValueItem::query()
            ->orderBy('sort_order')
            ->with('translations')
            ->get()
            ->map(fn ($item) => [
                'icon' => $item->icon,
                'title' => $item->translate($locale)?->title,
                'desc' => $item->translate($locale)?->description,
            ])->values()->all();

        $ceo = $team[0] ?? null;

        return [
            'about_data' => [
                'ceoName' => $ceo['name'] ?? null,
                'ceoRole' => $ceo['role'] ?? null,
                'heroStats' => $heroStats,
                'storyBadge' => $story?->badge,
                'storyTitle' => $story?->title,
                'storyDesc1' => $storyParagraphs[0] ?? null,
                'storyDesc2' => $storyParagraphs[1] ?? null,
                'storyDesc3' => $storyParagraphs[2] ?? null,
                'storyDesc4' => $storyParagraphs[3] ?? null,
                'storyCta' => $story?->cta_label,
                'storyImage' => MediaUrl::toPublicUrl($storySection?->settings['image'] ?? null),
                'heroImage' => MediaUrl::toPublicUrl(FrontendMediaImporter::resolvedPath('about-hero')),
                'aboutStats' => $stats,
                'missionTitle' => $mission?->title,
                'missionDesc' => $mission?->content,
                'visionTitle' => $vision?->title,
                'visionDesc' => $vision?->content,
                'valuesBadge' => $this->section('about_values_badge', $locale, 'What We Offer'),
                'valuesTitle' => $this->section('about_values_title', $locale, 'End-to-End Production Excellence'),
                'valuesList' => $values,
                'teamBadge' => $this->section('about_team_badge', $locale, 'Leadership'),
                'teamTitle' => $this->section('about_team_title', $locale, 'Leading The Untold Story'),
                'teamList' => $team,
                'partnersBadge' => $this->section('about_partners_badge', $locale, 'Trusted By Industry Titans'),
                'partnersTitle' => $this->section('about_partners_title', $locale, 'Trusted By Industry Titans'),
            ],
            'timeline' => $timeline,
            'skills' => $skills,
            'partner_labels' => PartnerLabel::query()
                ->orderBy('sort_order')
                ->with('translations')
                ->get()
                ->map(fn ($label) => $label->translate($locale)?->label)
                ->values()
                ->all(),
        ];
    }

    private function getServicesSections(string $locale): array
    {
        $whyList = FeatureHighlight::query()
            ->where('context', 'services')
            ->orderBy('sort_order')
            ->with('translations')
            ->get()
            ->map(fn ($item) => [
                'icon' => $item->icon,
                'title' => $item->translate($locale)?->title,
                'desc' => $item->translate($locale)?->description,
            ])->values()->all();

        return [
            'services_page' => [
                'gridBadge' => $this->section('services_grid_badge', $locale, 'Our Services'),
                'gridTitle' => $this->section('services_grid_title', $locale, 'Choose a service below'),
                'quoteTitle' => $this->settings->get('common.request_service', $locale),
                'quoteSubtext' => $this->section('services_quote_subtext', $locale, 'Send your brief and we will respond with the right plan and next steps.'),
                'quoteEmailLabel' => $this->section('contact_quote_email_label', $locale, 'Email'),
                'quoteEmail' => $this->settings->get('site.email', $locale),
                'quotePhoneLabel' => $this->section('contact_quote_phone_label', $locale, 'Phone and WhatsApp'),
                'quotePhone' => $this->settings->get('site.phone', $locale),
                'whyTitle' => $this->settings->get('common.why_us', $locale),
                'whyList' => $whyList,
                'ctaTitle' => $this->settings->get('common.request_service', $locale),
                'ctaSub' => $this->section('services_cta_sub', $locale, 'Send your brief and we will respond with the right plan and next steps.'),
                'ctaBtn' => $this->settings->get('common.request_service', $locale),
            ],
            'services' => $this->services->listAll($locale),
        ];
    }

    private function getPortfolioSections(string $locale): array
    {
        $stats = Stat::query()
            ->where('context', 'portfolio')
            ->orderBy('sort_order')
            ->with('translations')
            ->get()
            ->map(fn ($stat) => [
                'value' => (string) $stat->numeric_value.($stat->suffix ?? ''),
                'label' => $stat->translate($locale)?->label,
            ])->values()->all();

        $categories = Category::query()
            ->where('type', 'portfolio')
            ->orderBy('sort_order')
            ->with('translations')
            ->get()
            ->map(fn ($cat) => [
                'slug' => $cat->slug,
                'icon' => $cat->icon,
                'name' => $cat->translate($locale)?->name,
                'label' => $cat->translate($locale)?->label,
            ])->values()->all();

        return [
            'stats' => $stats,
            'categories' => $categories,
            'items' => $this->portfolio->list($locale)['items'],
        ];
    }

    private function getBlogSections(string $locale): array
    {
        $listing = $this->blog->list($locale);

        return [
            'posts' => $listing['items'],
            'categories' => Category::query()
                ->where('type', 'blog')
                ->orderBy('sort_order')
                ->with('translations')
                ->get()
                ->map(fn ($cat) => [
                    'slug' => $cat->slug,
                    'name' => $cat->translate($locale)?->name,
                ])->values()->all(),
            'pagination' => $listing['pagination'],
        ];
    }

    private function getContactSections(string $locale): array
    {
        $offices = Office::query()
            ->orderBy('sort_order')
            ->with('translations')
            ->get()
            ->map(fn ($office) => [
                'title' => $office->translate($locale)?->title,
                'flag' => $office->flag,
                'city' => $office->city,
                'country' => $office->country,
                'address' => $office->address,
                'phone' => $office->phone,
                'email' => $office->email,
                'time' => $office->timezone,
                'status' => $office->translate($locale)?->status,
            ])->values()->all();

        $whyList = FeatureHighlight::query()
            ->where('context', 'contact')
            ->orderBy('sort_order')
            ->with('translations')
            ->get()
            ->map(fn ($item) => [
                'icon' => $item->icon,
                'title' => $item->translate($locale)?->title,
                'desc' => $item->translate($locale)?->description,
            ])->values()->all();

        $resources = Resource::query()
            ->orderBy('sort_order')
            ->with('translations')
            ->get()
            ->map(fn ($resource) => [
                'icon' => $resource->icon,
                'color' => $resource->color,
                'title' => $resource->translate($locale)?->title,
                'typeLabel' => $resource->translate($locale)?->type_label,
                'fileUrl' => $resource->file_url,
            ])->values()->all();

        return [
            'contact_page' => [
                'infoTitle' => $this->section('contact_info_title', $locale, 'Contact Info'),
                'email' => $this->settings->get('site.email', $locale),
                'offices' => $offices,
                'officesSectionTitle' => $this->section('contact_offices_title', $locale, 'Our Offices'),
                'officesSectionSubtext' => $this->section('contact_offices_subtext', $locale, 'We are present in Egypt and the UAE to always be close to you'),
                'formTitle' => $this->section('contact_form_title', $locale, 'Send a Message'),
                'labels' => [
                    'name' => $this->section('contact_label_name', $locale, 'Full Name *'),
                    'email' => $this->section('contact_label_email', $locale, 'Email Address *'),
                    'phone' => $this->section('contact_label_phone', $locale, 'Phone Number'),
                    'service' => $this->section('contact_label_service', $locale, 'Requested Service'),
                    'message' => $this->section('contact_label_message', $locale, 'Your Message *'),
                    'chooseService' => $this->section('contact_label_choose_service', $locale, 'Choose a Service'),
                ],
            ],
            'why_list' => $whyList,
            'resources' => $resources,
            'services' => $this->services->listAll($locale),
        ];
    }

    private function section(string $key, string $locale, string $fallback): string
    {
        return $this->settings->get("sections.{$key}", $locale) ?? $fallback;
    }
}
