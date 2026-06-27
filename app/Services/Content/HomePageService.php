<?php

namespace App\Services\Content;

use App\Models\Award;
use App\Models\Faq;
use App\Models\HeroSlide;
use App\Models\Page;
use App\Models\PortfolioItem;
use App\Models\ProcessStep;
use App\Models\Service;
use App\Models\Stat;
use App\Models\Testimonial;
use App\Services\Seo\SeoService;

class HomePageService
{
    public function __construct(
        private readonly SeoService $seo,
        private readonly ServiceCatalogService $services,
        private readonly BlogService $blog,
    ) {}

    public function getHome(string $locale): array
    {
        $page = Page::query()
            ->where('slug', 'home')
            ->where('is_active', true)
            ->with(['translations', 'sections.translations'])
            ->first();

        $sections = $page?->sections->keyBy('type') ?? collect();
        $servicesIntro = $sections->get('services_intro');
        $ctaBanner = $sections->get('cta_banner');
        $servicesIntroT = $servicesIntro?->translate($locale);
        $ctaBannerT = $ctaBanner?->translate($locale);

        $photographyService = Service::query()
            ->where('slug', 'photography')
            ->with('translations')
            ->first();
        $photographyT = $photographyService?->translate($locale);

        return [
            'hero_slides' => $this->getHeroSlides($locale),
            'hero' => $this->getHeroFromFirstSlide($locale),
            'stats' => $this->getStats($locale),
            'services' => $this->services->listFeatured($locale),
            'home_data' => [
                'servicesBadge' => $servicesIntroT?->badge,
                'servicesTitle' => $servicesIntroT?->title,
                'servicesSubtext' => $servicesIntroT?->subtitle,
                'servicesCta' => $servicesIntroT?->content,
                'productionPipeline' => $servicesIntro?->settings['production_pipeline'] ?? [],
                'portfolioTitle' => $locale === 'ar' ? 'مشاريع من إنجاز The Untold Story' : 'Projects done by The Untold Story',
                'quoteBadge' => $locale === 'ar' ? 'حيث تلتقي القصة بالتنفيذ' : 'Where story meets execution',
                'quoteTitle' => $locale === 'ar' ? 'ميزانيات متوقعة. نتائج متميزة.' : 'Predictable budgets. Premium results.',
                'photographyBadge' => $locale === 'ar' ? 'تصوير يتماشى مع الفيلم' : 'Photography',
                'photographyTitle' => $photographyT?->title ?? 'Photography that matches the film.',
                'photographyDesc' => $photographyT?->short_desc,
                'photographyTagline' => $locale === 'ar' ? 'مصقول ومتناسق مع أسلوبك' : 'Polished and aligned with your tone',
                'creativeTitle' => 'Creative Creations',
                'creativeSubtitle' => 'Behind every Frame',
                'blogBadge' => $locale === 'ar' ? 'المدونة' : 'News & Insights',
                'blogTitle' => $locale === 'ar' ? 'آخر الأخبار والرؤى التسويقية' : 'News & Insights',
                'blogSubtext' => $locale === 'ar'
                    ? 'تابع أحدث المقالات والإلهامات حول الإعلان الرقمي، التسويق، والهوية البصرية.'
                    : 'Company news and updates',
                'ctaBannerTitle' => $ctaBannerT?->title,
                'ctaBannerText' => $ctaBannerT?->content,
            ],
            'work_showcase' => $this->getWorkShowcase($locale),
            'process' => $this->getProcess($locale),
            'testimonials' => $this->getTestimonials($locale),
            'photography' => [
                'badge' => $locale === 'ar' ? 'تصوير يتماشى مع الفيلم' : 'Photography',
                'title' => $photographyT?->title ?? 'Photography that matches the film.',
                'description' => $photographyT?->short_desc,
                'tagline' => $locale === 'ar' ? 'مصقول ومتناسق مع أسلوبك' : 'Polished and aligned with your tone',
                'icon' => $photographyService?->icon,
            ],
            'awards' => $this->getAwards($locale),
            'blog_preview' => $this->blog->list($locale, limit: 3)['items'],
            'faq' => $this->getFaq($locale),
            'cta_banner' => [
                'title' => $ctaBannerT?->title,
                'text' => $ctaBannerT?->content,
                'cta_label' => $ctaBannerT?->cta_label,
                'cta_url' => $ctaBannerT?->cta_url,
            ],
            'seo' => $this->seo->getForPageSlug('home', $locale),
        ];
    }

    private function getHeroSlides(string $locale): array
    {
        return HeroSlide::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with('translations')
            ->get()
            ->map(function (HeroSlide $slide) use ($locale) {
                $t = $slide->translate($locale);

                return [
                    'img' => $slide->image_url,
                    'gradient' => $slide->gradient,
                    'badge' => $t?->badge,
                    'title' => $t?->title,
                    'titleHighlight' => $t?->title_highlight,
                    'subtitle' => $t?->subtitle,
                    'desc' => $t?->description,
                    'cta' => $t?->cta_primary_label,
                    'ctaHref' => $t?->cta_primary_url,
                    'ctaSecondary' => $t?->cta_secondary_label,
                    'ctaSecondaryHref' => $t?->cta_secondary_url,
                ];
            })->values()->all();
    }

    private function getHeroFromFirstSlide(string $locale): array
    {
        $slide = HeroSlide::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with('translations')
            ->first();

        $t = $slide?->translate($locale);

        return [
            'badge' => $t?->badge,
            'headline1' => $t?->title,
            'headline2' => $t?->title_highlight,
            'subtext' => $t?->description,
            'cta1' => [
                'label' => $t?->cta_primary_label,
                'href' => $t?->cta_primary_url,
            ],
            'cta2' => [
                'label' => $t?->cta_secondary_label,
                'href' => $t?->cta_secondary_url,
            ],
            'image' => $slide?->image_url,
            'quoteBadge' => $t?->subtitle,
        ];
    }

    private function getStats(string $locale): array
    {
        return Stat::query()
            ->where('context', 'home')
            ->orderBy('sort_order')
            ->with('translations')
            ->get()
            ->map(function (Stat $stat) use ($locale) {
                $t = $stat->translate($locale);
                $value = (string) $stat->numeric_value.($stat->suffix ?? '');

                return [
                    'value' => $value,
                    'label' => $t?->label,
                    'icon' => $stat->icon,
                ];
            })->values()->all();
    }

    private function getWorkShowcase(string $locale): array
    {
        $items = PortfolioItem::query()
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->with(['translations', 'category.translations'])
            ->limit(4)
            ->get();

        if ($items->isEmpty()) {
            $items = PortfolioItem::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->with(['translations', 'category.translations'])
                ->limit(4)
                ->get();
        }

        return [
            'badge' => $locale === 'ar' ? '✨ أحدث أعمالنا' : '✨ Our Work',
            'title' => $locale === 'ar' ? 'إبداع يتجاوز التوقعات' : 'Projects done by The Untold Story',
            'subtitle' => $locale === 'ar'
                ? 'نماذج من مشاريعنا الناجحة في مختلف المجالات'
                : 'Film, video, advertising, documentaries, and corporate content',
            'viewAll' => $locale === 'ar' ? 'عرض جميع الأعمال' : 'Our Work',
            'projects' => $items->map(function (PortfolioItem $item) use ($locale) {
                $t = $item->translate($locale);
                $categoryT = $item->category?->translate($locale);

                return [
                    'slug' => $item->slug,
                    'img' => $item->image_url,
                    'category' => $categoryT?->name,
                    'title' => $t?->title,
                    'metric' => $t?->metric ?? $item->metric,
                    'size' => $item->grid_size,
                ];
            })->values()->all(),
        ];
    }

    private function getProcess(string $locale): array
    {
        $steps = ProcessStep::query()
            ->orderBy('sort_order')
            ->with('translations')
            ->get();

        return [
            'badge' => $locale === 'ar' ? 'دورة الإنتاج' : 'Production Cycle',
            'title' => $locale === 'ar'
                ? 'The Untold Story تقدّم دورة الإنتاج الكاملة'
                : 'The Untold Story delivers the full production cycle',
            'steps' => $steps->map(function (ProcessStep $step) use ($locale) {
                $t = $step->translate($locale);

                return [
                    'step' => (string) $step->step_number,
                    'title' => $t?->title,
                    'desc' => $t?->description,
                ];
            })->values()->all(),
        ];
    }

    private function getTestimonials(string $locale): array
    {
        $items = Testimonial::query()
            ->where('is_active', true)
            ->where('type', 'client')
            ->orderBy('sort_order')
            ->with('translations')
            ->get();

        return [
            'badge' => 'Creative Creations',
            'title' => 'Behind every Frame',
            'list' => $items->map(function (Testimonial $item) use ($locale) {
                $t = $item->translate($locale);

                return [
                    'name' => $t?->name,
                    'role' => $t?->role,
                    'text' => $t?->text,
                    'rating' => $item->rating,
                    'avatar' => $item->avatar_url,
                ];
            })->values()->all(),
        ];
    }

    private function getAwards(string $locale): array
    {
        return Award::query()
            ->orderBy('sort_order')
            ->with('translations')
            ->get()
            ->map(function (Award $award) use ($locale) {
                $t = $award->translate($locale);

                return [
                    'icon' => $award->icon,
                    'color' => $award->color,
                    'title' => $t?->title,
                    'organization' => $t?->organization,
                    'yearLabel' => $t?->year_label,
                ];
            })->values()->all();
    }

    private function getFaq(string $locale): array
    {
        $items = Faq::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with('translations')
            ->get();

        return [
            'badge' => $locale === 'ar' ? 'الأسئلة الشائعة' : 'FAQ',
            'title' => $locale === 'ar' ? 'أجوبة لأهم أسئلتك' : 'Answers to Your Key Questions',
            'list' => $items->map(function (Faq $faq) use ($locale) {
                $t = $faq->translate($locale);

                return [
                    'q' => $t?->question,
                    'a' => $t?->answer,
                ];
            })->values()->all(),
        ];
    }
}
