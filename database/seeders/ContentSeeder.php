<?php

namespace Database\Seeders;

use App\Models\Award;
use App\Models\BlogPost;
use App\Services\Media\FrontendMediaImporter;
use App\Models\Category;
use App\Models\ClientLogo;
use App\Models\Faq;
use App\Models\FeatureHighlight;
use App\Models\HeroSlide;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Office;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\PartnerLabel;
use App\Models\PortfolioItem;
use App\Models\ProcessStep;
use App\Models\Resource;
use App\Models\SeoMeta;
use App\Models\Service;
use App\Models\Setting;
use App\Models\SkillBar;
use App\Models\Stat;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\TimelineEvent;
use App\Models\ValueItem;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    private function media(string $key): string
    {
        return FrontendMediaImporter::resolvedPath($key);
    }

    public function run(): void
    {
        $this->seedSettings();
        $this->seedMenus();
        $this->seedPages();
        $this->seedHeroSlides();
        $this->seedStats();
        $this->seedServices();
        $this->seedProcessSteps();
        $this->seedTestimonials();
        $categories = $this->seedCategories();
        $this->seedPortfolioItems($categories);
        $this->seedBlogPosts($categories);
        $this->seedFaqs();
        $this->seedTeamMembers();
        $this->seedTimelineEvents();
        $this->seedSkillBars();
        $this->seedValueItems();
        $this->seedFeatureHighlights();
        $this->seedOffices();
        $this->seedPartnerLabels();
        $this->seedClientLogos();
        $this->seedResources();
        $this->seedAwards();
        $this->seedSeoMeta();
    }

    private function seedTranslations(object $model, array $en, array $ar): void
    {
        $model->translations()->create(array_merge(['locale' => 'en'], $en));
        $model->translations()->create(array_merge(['locale' => 'ar'], $ar));
    }

    private function parseStatValue(string $value): array
    {
        if (preg_match('/^(\d+)(.*)$/', $value, $matches)) {
            return [(int) $matches[1], $matches[2] !== '' ? $matches[2] : null];
        }

        return [0, $value];
    }

    private function seedSettings(): void
    {
        $pairs = [
            ['site.name', 'general', ['en' => 'The Untold Story', 'ar' => 'The Untold Story']],
            ['site.tagline', 'general', ['en' => 'Film & Video Production Studio', 'ar' => 'استوديو إنتاج أفلام وفيديو']],
            ['site.description', 'general', [
                'en' => 'Film & video production services in Egypt and MENA. The Untold Story delivers the full production cycle.',
                'ar' => 'خدمات إنتاج أفلام وفيديو في مصر ومنطقة الشرق الأوسط وشمال أفريقيا — دورة إنتاج كاملة من التخطيط إلى التسليم النهائي.',
            ]],
            ['site.email', 'contact', ['en' => 'bendary@globaluntoldstory.com', 'ar' => 'bendary@globaluntoldstory.com']],
            ['site.phone', 'contact', ['en' => '+201001299639', 'ar' => '+201001299639']],
            ['site.address', 'contact', ['en' => 'Egyptian Media Production City, Egypt', 'ar' => 'Egyptian Media Production City, Egypt']],
            ['site.working_hours', 'contact', ['en' => 'Sunday – Thursday, 9AM – 6PM', 'ar' => 'الأحد – الخميس، ٩ص – ٦م']],
            ['site.social_links', 'social', [
                'en' => json_encode(['instagram' => 'https://instagram.com', 'twitter' => 'https://twitter.com', 'linkedin' => 'https://linkedin.com', 'youtube' => 'https://youtube.com']),
                'ar' => json_encode(['instagram' => 'https://instagram.com', 'twitter' => 'https://twitter.com', 'linkedin' => 'https://linkedin.com', 'youtube' => 'https://youtube.com']),
            ]],
            ['announcement.text', 'banner', [
                'en' => 'Get a quote in 24h — Contact us for your next project',
                'ar' => 'احصل على عرض سعر خلال 24 ساعة — تواصل معنا لمشروعك القادم',
            ]],
            ['announcement.enabled', 'banner', ['en' => '1', 'ar' => '1']],
            ['footer.brand_desc', 'footer', [
                'en' => 'The Untold Story — a full-service film and video production studio in Egypt and MENA.',
                'ar' => 'The Untold Story — استوديو إنتاج أفلام وفيديو متكامل في مصر ومنطقة الشرق الأوسط وشمال أفريقيا.',
            ]],
        ];

        foreach ($pairs as [$key, $group, $values]) {
            $setting = Setting::query()->updateOrCreate(
                ['key' => $key],
                ['group' => $group, 'value' => null]
            );
            $setting->translations()->delete();
            $setting->translations()->create(['locale' => 'en', 'value' => $values['en']]);
            $setting->translations()->create(['locale' => 'ar', 'value' => $values['ar']]);
        }

        $commonKeys = [
            'common.dashboard_btn' => ['Dashboard', 'لوحة التحكم'],
            'common.read_more' => ['Read More', 'اقرأ المزيد'],
            'common.request_service' => ['Get a Quote', 'احصل على عرض سعر'],
            'common.contact_us' => ['Contact Us', 'تواصل معنا'],
            'common.contact_us_now' => ['Get in Touch Now', 'تواصل معنا الآن'],
            'common.explore_services' => ['Our Services', 'استكشف الخدمات'],
            'common.all_services' => ['More services for your needs. Get in touch', 'جميع الخدمات بالتفصيل'],
            'common.success_title' => ['Sent Successfully!', 'تم الإرسال بنجاح!'],
            'common.success_desc' => ['We have received your message and our team will get in touch within 24 hours.', 'وصلتنا رسالتك وسيتواصل معك فريقنا خلال ٢٤ ساعة.'],
            'common.send_another' => ['Send Another Message', 'إرسال رسالة أخرى'],
            'common.loading' => ['⏳ Sending...', '⏳ جارٍ الإرسال...'],
            'common.submit_btn' => ['Send Message', 'إرسال الرسالة'],
            'common.why_us' => ['Why The Untold Story?', 'لماذا The Untold Story?'],
        ];

        foreach ($commonKeys as $key => [$en, $ar]) {
            $setting = Setting::query()->updateOrCreate(['key' => $key], ['group' => 'common', 'value' => null]);
            $setting->translations()->delete();
            $setting->translations()->create(['locale' => 'en', 'value' => $en]);
            $setting->translations()->create(['locale' => 'ar', 'value' => $ar]);
        }
    }

    private function seedMenus(): void
    {
        $main = Menu::query()->updateOrCreate(['slug' => 'main'], ['name' => 'Main Navigation']);
        MenuItem::query()->where('menu_id', $main->id)->delete();

        $nav = [
            ['/', 'Home', 'الرئيسية'],
            ['/about', 'About Us', 'من نحن'],
            ['/services', 'Services', 'خدماتنا'],
            ['/portfolio', 'Portfolio', 'أعمالنا'],
            ['/blog', 'Blogs', 'المدونة'],
            ['/contact', 'Contact', 'تواصل معنا'],
        ];

        foreach ($nav as $i => [$url, $en, $ar]) {
            $item = MenuItem::query()->create([
                'menu_id' => $main->id,
                'url' => $url,
                'sort_order' => $i + 1,
                'is_active' => true,
            ]);
            $this->seedTranslations($item, ['label' => $en], ['label' => $ar]);
        }

        $footer = Menu::query()->updateOrCreate(['slug' => 'footer-about'], ['name' => 'Footer About Links']);
        MenuItem::query()->where('menu_id', $footer->id)->delete();
        $footerLinks = [
            ['/about', 'About us', 'من نحن'],
            ['/portfolio', 'Our Portfolio', 'أعمالنا'],
            ['/contact', 'Get in touch', 'تواصل معنا'],
        ];
        foreach ($footerLinks as $i => [$url, $en, $ar]) {
            $item = MenuItem::query()->create([
                'menu_id' => $footer->id,
                'url' => $url,
                'sort_order' => $i + 1,
                'is_active' => true,
            ]);
            $this->seedTranslations($item, ['label' => $en], ['label' => $ar]);
        }
    }

    private function seedPages(): void
    {
        $pages = [
            'home' => [
                'en' => ['title' => 'The Untold Story', 'subtitle' => 'Film & Video Production Studio', 'badge' => null],
                'ar' => ['title' => 'The Untold Story', 'subtitle' => 'استوديو إنتاج أفلام وفيديو', 'badge' => null],
            ],
            'about' => [
                'en' => ['title' => 'Where Ideas Come Alive and Stories Take Shape', 'subtitle' => 'We deliver the complete production cycle for brands, platforms, broadcasters, and institutions that need premium visuals with disciplined execution.', 'badge' => 'About Us'],
                'ar' => ['title' => 'حيث تولد الأفكار وتتشكّل القصص', 'subtitle' => 'نقدّم دورة الإنتاج الكاملة للعلامات التجارية والمنصات والقنوات والمؤسسات التي تحتاج مرئيات متميزة بتنفيذ منضبط.', 'badge' => 'من نحن'],
            ],
            'services' => [
                'en' => ['title' => 'Services', 'subtitle' => 'A full service film and video production studio across Egypt and MENA. Choose a service below or send your brief and we will recommend the right setup.', 'badge' => 'Services'],
                'ar' => ['title' => 'خدماتنا', 'subtitle' => 'استوديو إنتاج أفلام وفيديو متكامل في مصر ومنطقة الشرق الأوسط وشمال أفريقيا. اختر خدمة أدناه أو أرسل brief وسنوصي بالإعداد المناسب.', 'badge' => 'خدماتنا'],
            ],
            'portfolio' => [
                'en' => ['title' => 'Success Stories We Crafted', 'subtitle' => 'Explore some of our premium projects and campaigns that successfully scaled our clients\' business presence.', 'badge' => '🎨 Our Portfolio'],
                'ar' => ['title' => 'قصص نجاح صنعناها لعملائنا', 'subtitle' => 'نستعرض هنا بعضاً من أفضل أعمالنا وحملاتنا الإعلانية الناجحة التي ساهمت في نمو العلامات التجارية لشركائنا.', 'badge' => '🎨 معرض الأعمال'],
            ],
            'blog' => [
                'en' => ['title' => 'News & Insights', 'subtitle' => 'Company news and updates', 'badge' => 'News & Insights'],
                'ar' => ['title' => 'آخر الأخبار والرؤى التسويقية', 'subtitle' => 'تابع أحدث المقالات والإلهامات حول الإعلان الرقمي، التسويق، والهوية البصرية.', 'badge' => 'المدونة'],
            ],
            'contact' => [
                'en' => ['title' => 'We Are Here to Help', 'subtitle' => 'Send us a message and one of our experts will get in touch shortly.', 'badge' => 'Contact Us'],
                'ar' => ['title' => 'نحن هنا لمساعدتك', 'subtitle' => 'أرسل لنا رسالة وسيتواصل معك أحد خبرائنا في أقرب وقت ممكن', 'badge' => 'تواصل معنا'],
            ],
        ];

        foreach ($pages as $slug => $data) {
            $page = Page::query()->updateOrCreate(['slug' => $slug], ['is_active' => true]);
            $page->translations()->delete();
            $this->seedTranslations($page, $data['en'], $data['ar']);
            $page->sections()->delete();

            if ($slug === 'home') {
                $section = PageSection::query()->create([
                    'page_id' => $page->id,
                    'type' => 'services_intro',
                    'sort_order' => 1,
                    'settings' => ['production_pipeline' => ['Planning', 'Filming', 'Live', 'Post & final delivery', 'Localization']],
                    'is_active' => true,
                ]);
                $this->seedTranslations($section, [
                    'badge' => 'Our Storytelling & Production Expertise',
                    'title' => 'Our Storytelling & Production Expertise',
                    'subtitle' => 'End-to-end production for brands, platforms, broadcasters, and institutions',
                    'content' => 'More services for your needs. Get in touch',
                ], [
                    'badge' => 'خبرتنا في السرد والإنتاج',
                    'title' => 'خبرتنا في السرد والإنتاج',
                    'subtitle' => 'حلول إنتاج متكاملة للعلامات والمنصات والقنوات والمؤسسات',
                    'content' => 'المزيد من الخدمات لاحتياجاتك. تواصل معنا',
                ]);

                $cta = PageSection::query()->create([
                    'page_id' => $page->id,
                    'type' => 'cta_banner',
                    'sort_order' => 2,
                    'is_active' => true,
                ]);
                $this->seedTranslations($cta, [
                    'title' => 'Let\'s make it happen. Get in touch now!',
                    'content' => 'Contact us for your next project — get a quote in 24h',
                    'cta_label' => 'Contact Us',
                    'cta_url' => '/contact',
                ], [
                    'title' => 'لنحقق ذلك معاً. تواصل معنا الآن!',
                    'content' => 'تواصل معنا لمشروعك القادم — احصل على عرض سعر خلال 24 ساعة',
                    'cta_label' => 'تواصل معنا',
                    'cta_url' => '/contact',
                ]);
            }

            if ($slug === 'about') {
                $story = PageSection::query()->create([
                    'page_id' => $page->id,
                    'type' => 'story',
                    'sort_order' => 1,
                    'settings' => ['image' => $this->media('about-story-photo')],
                    'is_active' => true,
                ]);
                $this->seedTranslations($story, [
                    'badge' => 'Our Story',
                    'title' => 'The Untold Story is a full-service film and video production studio.',
                    'content' => implode("\n\n", [
                        'The Untold Story is where ideas come alive and stories take shape. From the first spark to the final frame, we craft cinematic experiences that captivate, move, and stay with audiences.',
                        'We create bold, unforgettable visuals across commercials, documentaries, branded films, live events, podcasts, photography, and motion/CGI, delivered with precision and flair. Multilingual production? We have that covered, too.',
                        'For international productions in Egypt, we handle everything on the ground, from permits, crews, logistics, and locations to full production support, so your vision unfolds seamlessly.',
                        'Beyond client projects, we develop original stories and formats designed to travel, inspire, and stay with audiences long after the screen fades.',
                    ]),
                    'cta_label' => 'Get in Touch',
                    'cta_url' => '/contact',
                ], [
                    'badge' => 'قصتنا',
                    'title' => 'The Untold Story استوديو إنتاج أفلام وفيديو متكامل الخدمات.',
                    'content' => implode("\n\n", [
                        'The Untold Story هو المكان الذي تولد فيه الأفكار وتتشكّل القصص. من الشرارة الأولى إلى الإطار الأخير، نصنع تجارب سينمائية تأسر وتُحرّك وتبقى في ذاكرة الجمهور.',
                        'نبتكر مرئيات جريئة لا تُنسى عبر الإعلانات التجارية والأفلام الوثائقية وأفلام العلامات التجارية والفعاليات الحية والبودكاست والتصوير والموشن/CGI، بإتقان ولمسة مميزة. إنتاج متعدد اللغات؟ نغطيه أيضاً.',
                        'للإنتاجات الدولية في مصر، نتولى كل شيء على أرض الواقع — من التصاريح والطاقم واللوجستيات والمواقع إلى الدعم الإنتاجي الكامل — حتى تتجسّد رؤيتك بسلاسة.',
                        'وراء مشاريع العملاء، نطوّر قصصاً وصيغاً أصلية مصمّمة للسفر والإلهام والبقاء مع الجمهور طويلاً بعد أن يخفت الشاشة.',
                    ]),
                    'cta_label' => 'تواصل معنا',
                    'cta_url' => '/contact',
                ]);

                foreach ([
                    ['mission', '🎯', 'Our Mission', 'We deliver the complete production cycle for brands, platforms, broadcasters, and institutions that need premium visuals with disciplined execution.', 'مهمتنا', 'تقديم دورة الإنتاج الكاملة للعلامات التجارية والمنصات والقنوات والمؤسسات التي تحتاج مرئيات متميزة بتنفيذ منضبط.'],
                    ['vision', '🔭', 'Our Vision', 'To craft cinematic experiences that captivate, move, and stay with audiences — from the first spark to the final frame.', 'رؤيتنا', 'صناعة تجارب سينمائية تأسر وتُحرّك وتبقى مع الجمهور — من الشرارة الأولى إلى الإطار الأخير.'],
                ] as $i => [$type, $icon, $enTitle, $enDesc, $arTitle, $arDesc]) {
                    $block = PageSection::query()->create([
                        'page_id' => $page->id,
                        'type' => $type,
                        'sort_order' => $i + 2,
                        'settings' => ['icon' => $icon],
                        'is_active' => true,
                    ]);
                    $this->seedTranslations($block, ['title' => $enTitle, 'content' => $enDesc], ['title' => $arTitle, 'content' => $arDesc]);
                }
            }
        }
    }

    private function seedHeroSlides(): void
    {
        HeroSlide::query()->delete();

        $slides = [
            [
                'sort_order' => 1,
                'image_url' => $this->media('hero-slide-1'),
                'gradient' => 'linear-gradient(160deg, rgba(0,0,0,0.82) 0%, rgba(0,0,0,0.55) 50%, rgba(0,0,0,0.35) 100%)',
                'en' => [
                    'badge' => '🎬 Film & Video Production services in Egypt and MENA',
                    'title' => 'The Untold Story delivers',
                    'title_highlight' => 'the full production cycle',
                    'subtitle' => 'Get a quote in 24h',
                    'description' => 'Film, video, advertising, documentaries, corporate content, TV shows, and more. With offices in Egypt Media Production City, Dubai, and Jeddah serving MENA and clients worldwide.',
                    'cta_primary_label' => 'Contact us for Next project',
                    'cta_primary_url' => '/contact',
                    'cta_secondary_label' => 'Our Work',
                    'cta_secondary_url' => '/portfolio',
                ],
                'ar' => [
                    'badge' => '🎬 إنتاج أفلام وفيديو في مصر ومنطقة الشرق الأوسط وشمال أفريقيا',
                    'title' => 'The Untold Story',
                    'title_highlight' => 'تقدّم دورة الإنتاج الكاملة',
                    'subtitle' => 'احصل على عرض سعر خلال 24 ساعة',
                    'description' => 'أفلام، فيديو، إعلانات، أفلام وثائقية، محتوى مؤسسي، برامج تلفزيونية، والمزيد. مكاتبنا في مدينة الإنتاج الإعلامي بمصر ودبي وجدة لخدمة المنطقة والعملاء حول العالم.',
                    'cta_primary_label' => 'تواصل معنا للمشروع القادم',
                    'cta_primary_url' => '/contact',
                    'cta_secondary_label' => 'أعمالنا',
                    'cta_secondary_url' => '/portfolio',
                ],
            ],
            [
                'sort_order' => 2,
                'image_url' => $this->media('hero-slide-2'),
                'gradient' => 'linear-gradient(160deg, rgba(0,0,0,0.78) 0%, rgba(0,0,0,0.5) 100%)',
                'en' => [
                    'badge' => 'Where story meets execution',
                    'title' => 'Predictable budgets',
                    'title_highlight' => 'Premium results.',
                    'subtitle' => 'Our Storytelling & Production Expertise',
                    'description' => 'End-to-end production for brands, platforms, broadcasters, and institutions',
                    'cta_primary_label' => 'Contact Us',
                    'cta_primary_url' => '/contact',
                    'cta_secondary_label' => 'Our Work',
                    'cta_secondary_url' => '/portfolio',
                ],
                'ar' => [
                    'badge' => 'حيث تلتقي القصة بالتنفيذ',
                    'title' => 'ميزانيات متوقعة',
                    'title_highlight' => 'نتائج متميزة.',
                    'subtitle' => 'خبرتنا في السرد والإنتاج',
                    'description' => 'حلول إنتاج متكاملة للعلامات والمنصات والقنوات والمؤسسات',
                    'cta_primary_label' => 'تواصل معنا',
                    'cta_primary_url' => '/contact',
                    'cta_secondary_label' => 'أعمالنا',
                    'cta_secondary_url' => '/portfolio',
                ],
            ],
        ];

        foreach ($slides as $slideData) {
            $en = $slideData['en'];
            $ar = $slideData['ar'];
            unset($slideData['en'], $slideData['ar']);
            $slide = HeroSlide::query()->create(array_merge($slideData, ['is_active' => true]));
            $this->seedTranslations($slide, $en, $ar);
        }
    }

    private function seedStats(): void
    {
        Stat::query()->delete();

        $homeStats = [
            ['3+', '🌍', 'Offices across MENA', 'مكاتب في المنطقة', 'home'],
            ['50+', '🤝', 'Satisfied clients', 'عميل راضٍ', 'home'],
            ['90%', '⭐', 'Repeat business rate', 'معدل العملاء المتكررين', 'home'],
            ['360°', '🎬', 'Full production cycle', 'دورة إنتاج متكاملة', 'home'],
        ];

        foreach ($homeStats as $i => [$value, $icon, $enLabel, $arLabel, $context]) {
            [$numeric, $suffix] = $this->parseStatValue($value);
            $stat = Stat::query()->create([
                'icon' => $icon,
                'numeric_value' => $numeric,
                'suffix' => $suffix,
                'sort_order' => $i + 1,
                'context' => $context,
            ]);
            $this->seedTranslations($stat, ['label' => $enLabel], ['label' => $arLabel]);
        }

        $portfolioStats = [
            ['500+', null, 'Projects Done', 'مشروع منجز'],
            ['120+', null, 'Happy Clients', 'عميل راضٍ'],
            ['15', null, 'Countries', 'دولة'],
        ];
        foreach ($portfolioStats as $i => [$value, $icon, $enLabel, $arLabel]) {
            [$numeric, $suffix] = $this->parseStatValue($value);
            $stat = Stat::query()->create([
                'icon' => $icon,
                'numeric_value' => $numeric,
                'suffix' => $suffix,
                'sort_order' => $i + 1,
                'context' => 'portfolio',
            ]);
            $this->seedTranslations($stat, ['label' => $enLabel], ['label' => $arLabel]);
        }
    }

    private function seedServices(): void
    {
        Service::query()->delete();

        // [slug, icon, image_key, enTitle, arTitle, enDesc, arDesc]
        // The first 4 entries are the featured service cards (800×540 images)
        $services = [
            ['on-ground-egypt', '🇪🇬', 'svc-on-ground', 'svc-all-on-ground', 'On Ground Production Services in Egypt', 'خدمات الإنتاج على أرض الواقع في مصر', 'Permits, fixing, crew, gear, logistics, and location scouting for international shoots across Egypt.', 'تصاريح، fixing، طاقم، معدات، لوجستيات، وlocation scouting للتصوير الدولي في مصر.'],
            ['commercial', '📺', 'svc-commercial', 'svc-all-commercial', 'Commercial Advertising Production', 'إنتاج الإعلانات التجارية', 'High impact commercials and campaign films from concept to filming and final delivery.', 'إعلانات وأفلام حملات عالية التأثير من الفكرة إلى التصوير والتسليم النهائي.'],
            ['documentary', '🎬', 'svc-documentary', 'svc-all-documentary', 'Documentary Production', 'إنتاج الأفلام الوثائقية', 'Research led documentaries and doc series built for platforms, broadcasters, and institutions.', 'أفلام وثائقية وسلاسل doc مبنية على البحث للمنصات والقنوات والمؤسسات.'],
            ['corporate', '🏢', 'svc-corporate', 'svc-all-corporate', 'Corporate and Industrial Content', 'المحتوى المؤسسي والصناعي', 'Clear, credible corporate films for oil and gas, education, real estate, and complex industries.', 'أفلام مؤسسية واضحة وموثوقة للنفط والغاز والتعليم والعقارات والصناعات المعقدة.'],
            ['events', '🎤', null, 'svc-all-events', 'Event Coverage and Live Production', 'تغطية الفعاليات والإنتاج المباشر', 'Multi camera coverage, live streaming, drone, and same day edits for conferences, launches, and concerts.', 'تغطية متعددة الكameras، بث مباشر، طائرات بدون طيار، ومontages في نفس اليوم للمؤتmerات والإطلاقات والحفلات.'],
            ['tv-broadcast', '📡', null, 'svc-all-tv-broadcast', 'TV Shows and Live Broadcast Production', 'إنتاج البرامج التلفزيونية والبث المباشر', 'Studio and field production with control room workflow, run downs, graphics packages, and delivery.', 'إنتاج استودio وميداني مع control room workflow وrun downs وحزم graphics وتسليم.'],
            ['podcast', '🎙️', null, 'svc-all-podcast', 'Podcast Production', 'إنتاج البودكاست', 'Audio and video podcast production with studio or on location setup, editing, and clips for distribution.', 'إنتاج بودكast صوتي ومرئي في الاستودio أو on location، مع montage ومقاطع للتوزيع.'],
            ['post-production', '✂️', null, 'svc-all-post-production', 'Post Production and Final Delivery', 'ما بعد الإنتاج والتسليم النهائي', 'Editing, color, sound, mastering, and multi format versions that meet platform and broadcast specs.', 'مونتاج، ألوان، صوت، mastering، ونسخ متعددة الصيغ تلبي مواصفات المنصات والبث.'],
            ['motion-cgi', '✨', null, 'svc-all-motion-cgi', 'Motion CGI and AI Powered Visuals', 'الموشن CGI والمرئيات المدعومة بالذكاء الاصطناعي', '2D motion, 3D, CGI, and AI assisted visuals to elevate storytelling and explain complex ideas.', 'موشن 2D و3D وCGI ومرئيات بمساعدة AI لرفع السرد وشرح الأفكار المعقدة.'],
            ['dubbing', '🌐', null, 'svc-all-dubbing', 'Dubbing Voice Over and Localization', 'الدبلجة والتعليق الصوتي والتعريب', 'Subtitles, voice over, dubbing, ADR, and multi language packaging aligned with delivery standards.', 'ترجمة، تعليق صوتي، دبلجة، ADR، وتعبئة متعددة اللغات وفق معايير التسليم.'],
            ['photography', '📷', null, 'svc-all-photography', 'Photography', 'التصوير', 'Premium photography for brands, products, executives, and events with clean lighting and consistent delivery.', 'تصوير متميز للعلامات والمنتجات والتنفيذيين والفعاليات بإضاءة نظيفة وتسليم متسق.'],
            ['marketing', '📊', null, 'svc-all-marketing', 'Marketing Solutions and Performance', 'حلول التسويق والأداء', 'Content strategy, media buying, creative testing, and campaign systems built around production.', 'استراتيجية محتوى، media buying، اختبار إبداعي، وأنظمة حملات مبنية حول الإنتاج.'],
            ['original-ip', '💡', null, 'svc-all-original-ip', 'Original IP Development', 'تطوير الملكية الفكرية الأصلية', 'Original story formats and creative IP developed in-house, designed to travel across markets and inspire audiences.', 'صيغ قصصية أصلية وملكية فكرية إبداعية مطوّرة داخلياً، مصمّمة للسفر عبر الأسواق وإلهام الجمهور.'],
        ];

        foreach ($services as $i => [$slug, $icon, $featuredImgKey, $allImgKey, $enTitle, $arTitle, $enDesc, $arDesc]) {
            $imgKey = $featuredImgKey ?? $allImgKey;
            $service = Service::query()->create([
                'slug' => $slug,
                'icon' => $icon,
                'image_url' => $imgKey ? $this->media($imgKey) : null,
                'sort_order' => $i + 1,
                'is_active' => true,
                'is_featured' => $i < 4,
            ]);
            $this->seedTranslations($service, [
                'title' => $enTitle,
                'short_desc' => $enDesc,
                'full_desc' => $enDesc,
                'price' => null,
            ], [
                'title' => $arTitle,
                'short_desc' => $arDesc,
                'full_desc' => $arDesc,
                'price' => null,
            ]);
        }
    }

    private function seedProcessSteps(): void
    {
        ProcessStep::query()->delete();

        $steps = [
            [1, 'Planning', 'Concept development, scripting, storyboards, and scheduling.', 'التخطيط', 'تطوير الفكرة، السيناريو، storyboard، والجدول الزمني.'],
            [2, 'Filming', 'Professional cinematic filming in studio or on location.', 'التصوير', 'تصوير سينمائي احترافي في الاستوديو أو على location.'],
            [3, 'Live', 'Live event coverage and real-time production support.', 'البث المباشر', 'تغطية حية للفعاليات والبث المباشر.'],
            [4, 'Post & final delivery', 'Editing, color grading, VFX, and final delivery.', 'ما بعد الإنتاج', 'مونتاج، تصحيح ألوان، VFX، وتسليم نهائي.'],
            [5, 'Localization', 'Dubbing, translation, and localization for multiple markets.', 'التعريب', 'دبلجة وترجمة وتعريb لأسواق متعددة.'],
        ];

        foreach ($steps as $i => [$num, $enTitle, $enDesc, $arTitle, $arDesc]) {
            $step = ProcessStep::query()->create(['step_number' => $num, 'sort_order' => $i + 1]);
            $this->seedTranslations($step, ['title' => $enTitle, 'description' => $enDesc], ['title' => $arTitle, 'description' => $arDesc]);
        }
    }

    private function seedTestimonials(): void
    {
        Testimonial::query()->delete();

        $items = [
            [
                'avatar' => $this->media('portrait-man-1'),
                'type' => 'client',
                'en' => ['name' => 'Commercial Production', 'role' => 'Bold visuals for brands', 'text' => 'From concept to final frame — commercials that captivate, move, and stay with audiences.'],
                'ar' => ['name' => 'أحمد الشمري', 'role' => 'مدير تسويق، شركة النخبة', 'text' => 'Untold غيّرت طريقة تفكيرنا في التسويق الرقمي. النتائج تجاوزت توقعاتنا بكثير.'],
            ],
            [
                'avatar' => $this->media('cinema-camera'),
                'type' => 'client',
                'en' => ['name' => 'Documentary Production', 'role' => 'Stories that travel', 'text' => 'Real stories crafted with cinematic precision and disciplined execution.'],
                'ar' => ['name' => 'سارة القحطاني', 'role' => 'رائدة أعمال', 'text' => 'تعاملت مع وكالات كثيرة لكن Untold الأكثر احترافية والأسرع في التنفيذ. أنصح بها بشدة.'],
            ],
            [
                'avatar' => $this->media('portrait-man-2'),
                'type' => 'client',
                'en' => ['name' => 'Post Production', 'role' => 'Premium finishing', 'text' => 'Editing, color, VFX, and sound — polished and aligned with your tone.'],
                'ar' => ['name' => 'محمد العتيبي', 'role' => 'مؤسس، براند ماكس', 'text' => 'زادت مبيعاتنا ٣٠٠٪ خلال ٦ أشهر من العمل مع Untold. فريق رائع!'],
            ],
            [
                'avatar' => $this->media('camera-equipment'),
                'type' => 'client',
                'en' => ['name' => 'On-Ground Production', 'role' => 'Egypt & MENA', 'text' => 'Permits, crews, logistics, locations, and full on-the-ground support for international shoots.'],
                'ar' => ['name' => 'لطيفة الدوسري', 'role' => 'مديرة تنفيذية، ريادة', 'text' => 'الاحترافية والإبداع في كل تفصيلة. لن أتعامل مع أي وكالة أخرى بعد الآن.'],
            ],
            [
                'avatar' => $this->media('executive-portrait'),
                'type' => 'ceo',
                'en' => ['name' => 'Khaled Bendary', 'role' => 'CEO, The Untold Story', 'text' => '"Predictable budgets. Premium results. The Untold Story delivers the full production cycle with disciplined execution."'],
                'ar' => ['name' => 'Khaled Bendary', 'role' => 'CEO, The Untold Story', 'text' => '"ميزانيات متوقعة. نتائج متميزة. The Untold Story تقدّم دورة الإنتاج الكاملة بتنفيذ منضبط."'],
            ],
        ];

        foreach ($items as $i => $item) {
            $testimonial = Testimonial::query()->create([
                'avatar_url' => $item['avatar'],
                'rating' => 5,
                'sort_order' => $i + 1,
                'is_active' => true,
                'type' => $item['type'],
            ]);
            $this->seedTranslations($testimonial, $item['en'], $item['ar']);
        }
    }

    private function seedCategories(): array
    {
        Category::query()->delete();
        $map = [];

        $portfolioCats = [
            ['digital-ads', '📊', 'Digital Ads', '📊 Digital Ads', 'Digital Ads', '📊 الإعلانات الرقمية'],
            ['branding', '🎨', 'Branding', '🎨 Branding', 'Branding', '🎨 الهوية البصرية'],
            ['video', '🎬', 'Video Production', '🎬 Video Production', 'Video Production', '🎬 إنتاج الفيديو'],
            ['social', '📱', 'Social Media', '📱 Social Media', 'Social Media', '📱 السوشيال ميديا'],
        ];
        foreach ($portfolioCats as $i => [$slug, $icon, $enName, $enLabel, $arName, $arLabel]) {
            $cat = Category::query()->create(['slug' => $slug, 'type' => 'portfolio', 'icon' => $icon, 'sort_order' => $i + 1]);
            $this->seedTranslations($cat, ['name' => $enName, 'label' => $enLabel], ['name' => $arName, 'label' => $arLabel]);
            $map['portfolio'][$slug] = $cat->id;
        }

        $blogCats = [
            ['digital-ads', 'Digital Ads', 'إعلان رقمي'],
            ['design', 'Design', 'تصميم'],
            ['content', 'Content', 'محتوى'],
            ['marketing-strategy', 'Marketing Strategy', 'استراتيجية تسويقية'],
        ];
        foreach ($blogCats as $i => [$slug, $enName, $arName]) {
            $cat = Category::query()->create(['slug' => $slug, 'type' => 'blog', 'icon' => null, 'sort_order' => $i + 1]);
            $this->seedTranslations($cat, ['name' => $enName, 'label' => $enName], ['name' => $arName, 'label' => $arName]);
            $map['blog'][$slug] = $cat->id;
        }

        return $map;
    }

    private function seedPortfolioItems(array $categories): void
    {
        PortfolioItem::query()->delete();

        $projects = [
            ['al-nokhba-digital-marketing', 'digital-ads', 'Al-Nokhba Group', 'digital-marketing', '3 Months', '$14,000', '+240% Sales Increase, 5.2x ROI', '+240% Sales', 'large', 'Al-Nokhba Digital Marketing Blitz', 'حملة النخبة للتسويق الرقمي', '+240% Sales Increase, 5.2x ROI', 'زيادة المبيعات +٢٤٠٪، عائد الاستثمار ٥.٢ ضعف'],
            ['rikaz-real-estate-identity', 'branding', 'Rikaz Development', 'branding-design', '1 Month', '$7,000', 'Unique modern logo & comprehensive brand guidelines', 'Full Identity', 'small', 'Rikaz Real Estate Visual Identity', 'الهوية البصرية لشركة ركاز العقارية', 'Unique modern logo & comprehensive brand guidelines', 'تصميم شعار فريد ودليل هوية بصرية كاملة'],
            ['masar-app-launch-video', 'video', 'Masar EdTech', 'gaming-video', '2 Months', '$9,500', '3.5M Views, +180% App Downloads boost', '3.5M Views', 'large', 'Masar Learning App Launch Video', 'فيديو إطلاق تطبيق مسار التعليمي', '3.5M Views, +180% App Downloads boost', '٣.٥ مليون مشاهدة، زيادة التحميلات +١٨٠٪'],
            ['gourmet-social-growth', 'social', 'Gourmet International', 'social-media', '6 Months', '$5,000/mo', '+45k New followers, +130% Monthly engagement', '+45k Followers', 'small', 'Gourmet Burgers Social Growth & Content', 'إدارة السوشيال ميديا لمطاعم جورميه', '+45k New followers, +130% Monthly engagement', '+٤٥ ألف متابع جديد، تفاعل شهري +١٣٠٪'],
            ['fashion-brand-google-campaign', 'digital-ads', 'Fashion Brand Ltd', 'fashion-retail', '4 Months', '$22,000', '+320% Return on Ad Spend, 8,000+ Conversions', '+320% ROAS', 'small', 'Fashion Brand Google Search Campaign', 'حملة إعلانات جوجل لبراند فاشن', '+320% Return on Ad Spend, 8,000+ Conversions', '+٣٢٠٪ عائد على الإنفاق الإعلاني، ٨ آلاف تحويل'],
            ['myhealth-influencer-campaign', 'social', 'MyHealth Ltd', 'gaming-arena', '2 Months', '$11,000', 'Featured by 15 major fitness influencers, sold out stock', 'Sold Out', 'small', 'MyHealth Influencer Launch Campaign', 'حملة المؤثرين لمنتج صحتي الرياضي', 'Featured by 15 major fitness influencers, sold out stock', 'تغطية من ١٥ مؤثر، مبيعات نفدت بالكامل'],
        ];

        foreach ($projects as $i => $p) {
            [$slug, $catSlug, $client, $mediaKey, $duration, $budget, $results, $metric, $grid, $enTitle, $arTitle, $enResults, $arResults] = $p;
            $item = PortfolioItem::query()->create([
                'slug' => $slug,
                'category_id' => $categories['portfolio'][$catSlug] ?? null,
                'client_name' => $client,
                'image_url' => $this->media($mediaKey),
                'duration' => $duration,
                'budget' => $budget,
                'results' => $results,
                'metric' => $metric,
                'sort_order' => $i + 1,
                'is_featured' => in_array($grid, ['large'], true),
                'is_active' => true,
                'grid_size' => $grid,
            ]);
            $this->seedTranslations($item, [
                'title' => $enTitle,
                'results_text' => $enResults,
                'metric' => $metric,
            ], [
                'title' => $arTitle,
                'results_text' => $arResults,
                'metric' => $metric,
            ]);
        }
    }

    private function seedBlogPosts(array $categories): void
    {
        BlogPost::query()->delete();

        $featured = BlogPost::query()->create([
            'slug' => 'future-of-digital-marketing-2024',
            'category_id' => $categories['blog']['marketing-strategy'] ?? null,
            'author_name' => 'Ahmed Al-Zahrani',
            'author_image_url' => $this->media('portrait-man-1'),
            'featured_image_url' => $this->media('blog-film-production'),
            'published_at' => Carbon::parse('2024-06-01'),
            'read_time_minutes' => 8,
            'is_featured' => true,
            'is_published' => true,
            'sort_order' => 0,
        ]);
        $this->seedTranslations($featured, [
            'title' => 'The Future of Digital Marketing in 2024: 10 Trends That Will Change the Game',
            'excerpt' => 'A comprehensive overview of the most important shifts in digital marketing this year, from AI to influencer marketing and interactive content.',
            'body' => null,
            'tags' => ['AI Marketing', '2024', 'Trends'],
        ], [
            'title' => 'مستقبل التسويق الرقمي في 2024: 10 اتجاهات ستغيّر قواعد اللعبة',
            'excerpt' => 'استعراض شامل لأهم التحولات في عالم التسويق الرقمي خلال العام الجاري، من الذكاء الاصطناعي إلى التسويق عبر المؤثرين والمحتوى التفاعلي.',
            'body' => null,
            'tags' => ['AI تسويق', '2024', 'اتجاهات'],
        ]);

        $posts = [
            ['post-1', 'digital-ads', '2026-06-20', 5, 'How to Boost ROI from Your Digital Campaigns', 'كيف ترفع عائد الاستثمار من حملاتك الرقمية', 'Discover the secrets of audience targeting, message crafting, and analytics optimization.', 'أسرار اختيار الجمهور الصحيح، صياغة الرسالة الأمثل، وتحليل الأرقام لتحسين الأداء.', 'Digital Ads', 'إعلان رقمي'],
            ['post-2', 'design', '2026-06-15', 4, 'Top 5 Brand Identity Trends for 2026', 'أهم ٥ اتجاهات في تصميم الهوية البصرية لعام ٢٠٢٦', 'Learn the design cues that will help your brand stand out in a competitive market.', 'اكتشف ملامح التصميم التي تساعد علامتك التجارية على البروز في السوق.', 'Design', 'تصميم'],
            ['post-3', 'content', '2026-06-10', 3, 'Creative Content Strategy Techniques That Work', 'طرق مبتكرة لبناء استراتيجية محتوى فعّالة', 'From idea to distribution — how to build content that attracts and retains customers.', 'من الفكرة إلى التوزيع — كيف تصنع محتوى يجذب العملاء ويحتفظ بهم.', 'Content', 'محتوى'],
        ];

        foreach ($posts as $i => [$slug, $cat, $date, $readTime, $enTitle, $arTitle, $enExcerpt, $arExcerpt, $enCat, $arCat]) {
            $post = BlogPost::query()->create([
                'slug' => $slug,
                'category_id' => $categories['blog'][$cat] ?? null,
                'author_name' => 'The Untold Story',
                'featured_image_url' => null,
                'published_at' => Carbon::parse($date),
                'read_time_minutes' => $readTime,
                'is_featured' => false,
                'is_published' => true,
                'sort_order' => $i + 1,
            ]);
            $this->seedTranslations($post, [
                'title' => $enTitle,
                'excerpt' => $enExcerpt,
                'body' => null,
                'tags' => [$enCat],
            ], [
                'title' => $arTitle,
                'excerpt' => $arExcerpt,
                'body' => null,
                'tags' => [$arCat],
            ]);
        }
    }

    private function seedFaqs(): void
    {
        Faq::query()->delete();

        $faqs = [
            ['How long does a brand identity take?', 'Typically 10-14 business days, including research, design drafts, and revisions.', 'كم يستغرق تنفيذ الهوية البصرية؟', 'عادةً ١٠–١٤ يوم عمل، تشمل مراحل البحث والتصميم والمراجعات.'],
            ['Do you work with small businesses?', 'Yes, we have tailored packages designed for setups of all sizes, from startups to corporates.', 'هل تعملون مع الشركات الصغيرة؟', 'نعم، لدينا باقات مناسبة لكل الأحجام من الفرد حتى الشركة الكبيرة.'],
            ['What are the payment terms?', 'We accept bank transfers and major cards. Terms are usually 50% upfront and 50% upon delivery.', 'كيف يتم الدفع؟', 'نقبل الدفع بالتحويل البنكي أو المدى أو الفيزا. عادةً ٥٠٪ مقدم و٥٠٪ عند التسليم.'],
            ['Do you provide campaign reports?', 'Absolutely. We supply detailed weekly and monthly reports tracking all major key performance indicators (KPIs).', 'هل تقدمون تقارير للحملات؟', 'بالطبع، تقارير أسبوعية وشهرية مفصّلة مع جميع المؤشرات الرئيسية.'],
            ['Can I upgrade or downgrade my plan later?', 'Yes, our plans are flexible and can be adapted at any time according to your business needs.', 'هل يمكنني تعديل الباقة لاحقاً؟', 'نعم، باقاتنا مرنة ويمكن تعديلها في أي وقت حسب احتياجاتك.'],
        ];

        foreach ($faqs as $i => [$enQ, $enA, $arQ, $arA]) {
            $faq = Faq::query()->create(['sort_order' => $i + 1, 'is_active' => true]);
            $this->seedTranslations($faq, ['question' => $enQ, 'answer' => $enA], ['question' => $arQ, 'answer' => $arA]);
        }
    }

    private function seedTeamMembers(): void
    {
        TeamMember::query()->delete();

        $member = TeamMember::query()->create([
            'slug' => 'khaled-bendary',
            'image_url' => $this->media('executive-portrait'),
            'sort_order' => 1,
            'is_active' => true,
        ]);
        $this->seedTranslations($member, [
            'name' => 'Khaled Bendary',
            'role' => 'CEO',
            'bio' => 'Leading The Untold Story with a vision for premium visual production and disciplined execution across the MENA region.',
        ], [
            'name' => 'Khaled Bendary',
            'role' => 'المدير التنفيذي',
            'bio' => 'يقود The Untold Story برؤية لإنتاج مرئي متميز بتنفيذ منضبط عبر منطقة الشرق الأوسط وشمال أفريقيا.',
        ]);
    }

    private function seedTimelineEvents(): void
    {
        TimelineEvent::query()->delete();

        $events = [
            ['2015', '🎬', 'Studio Founded', 'Launched as a boutique film and video production studio with a vision for cinematic storytelling', 'تأسيس الاستوديو', 'انطلقنا كاستوديو إنتاج أفلام وفيديو متخصص برؤية للسرد السينمائي'],
            ['2018', '🌍', 'MENA Expansion', 'Opened offices across the MENA region to serve brands, platforms, and broadcasters', 'التوسع في المنطقة', 'افتتحنا مكاتب في منطقة الشرق الأوسط وشمال أفريقيا لخدمة العلامات والمنصات والقنوات'],
            ['2020', '🤝', '50+ Clients', 'Reached 50+ satisfied clients with a growing repeat business rate', '50+ عميل', 'وصلنا إلى أكثر من 50 عميل راضٍ مع معدل متزايد من العمل المتكرر'],
            ['2022', '🇪🇬', 'Egypt Production Hub', 'Established full on-the-ground production support for international shoots in Egypt', 'مركز الإنتاج في مصر', 'أنشأنا دعماً إنتاجياً كاملاً على أرض الواقع للتصوير الدولي في مصر'],
            ['2024', '✨', 'Original Formats', 'Expanded into original stories and formats designed to travel and inspire global audiences', 'صيغ أصلية', 'توسّعنا في تطوير قصص وصيغ أصلية مصمّمة للسفر والإلهام'],
        ];

        foreach ($events as $i => [$year, $icon, $enTitle, $enDesc, $arTitle, $arDesc]) {
            $event = TimelineEvent::query()->create(['year' => $year, 'icon' => $icon, 'sort_order' => $i + 1]);
            $this->seedTranslations($event, ['title' => $enTitle, 'description' => $enDesc], ['title' => $arTitle, 'description' => $arDesc]);
        }
    }

    private function seedSkillBars(): void
    {
        SkillBar::query()->delete();

        $skills = [
            [97, '#6366f1', 'Commercial Production', 'الإعلانات التجارية'],
            [94, '#f59e0b', 'Documentaries & Branded Films', 'الأفلام الوثائقية والعلامات التجارية'],
            [92, '#10b981', 'Live Events & Podcasts', 'الفعاليات الحية والبودكاست'],
            [90, '#8b5cf6', 'Motion/CGI & Photography', 'الموشن/CGI والتصوير'],
            [95, '#ef4444', 'Multilingual Production', 'الإنتاج متعدد اللغات'],
            [93, '#06b6d4', 'Egypt Ground Support', 'الدعم الإنتاجي في مصر'],
        ];

        foreach ($skills as $i => [$percent, $color, $enLabel, $arLabel]) {
            $bar = SkillBar::query()->create(['percent' => $percent, 'color' => $color, 'sort_order' => $i + 1]);
            $this->seedTranslations($bar, ['label' => $enLabel], ['label' => $arLabel]);
        }
    }

    private function seedValueItems(): void
    {
        ValueItem::query()->delete();

        $values = [
            ['🎬', 'Full Production Cycle', 'From concept to final frame, we handle every stage with precision and flair.', 'دورة إنتاج متكاملة', 'من الفكرة إلى الإطار النهائي، نتولى كل مرحلة بدقة ولمسة مميزة.'],
            ['🌐', 'Multilingual Production', 'Global-ready production across languages and markets — we\'ve got you covered.', 'إنتاج متعدد اللغات', 'إنتاج جاهز للعالم عبر اللغات والأسواق — نغطيه بالكامل.'],
            ['🇪🇬', 'Egypt Production Hub', 'Permits, crews, logistics, locations, and full on-the-ground support for international shoots.', 'مركز الإنتاج في مصر', 'تصاريح وطاقم ولوجستيات ومواقع ودعم كامل على أرض الواقع للتصوير الدولي.'],
            ['✨', 'Original Storytelling', 'Beyond client work, we develop formats designed to travel, inspire, and endure.', 'سرد أصلي', 'نطوّر قصصاً وصيغاً مصمّمة للسفر والإلهام والبقاء.'],
        ];

        foreach ($values as $i => [$icon, $enTitle, $enDesc, $arTitle, $arDesc]) {
            $item = ValueItem::query()->create(['icon' => $icon, 'sort_order' => $i + 1]);
            $this->seedTranslations($item, ['title' => $enTitle, 'description' => $enDesc], ['title' => $arTitle, 'description' => $arDesc]);
        }
    }

    private function seedFeatureHighlights(): void
    {
        FeatureHighlight::query()->delete();

        $servicesWhy = [
            ['🎬', 'Full Production Cycle', 'From planning and filming to post, localization, and final delivery.', 'دورة إنتاج متكاملة', 'من التخطيط والتصوير إلى ما بعد الإنتاج والتعريب والتسليم النهائي.'],
            ['🌍', 'MENA & Global Reach', 'Offices in Egypt, Dubai, and Jeddah serving clients worldwide.', 'المنطقة والعالم', 'مكاتب في مصر ودبي وجدة لخدمة العملاء حول العالم.'],
            ['🇪🇬', 'On-Ground in Egypt', 'Permits, crews, gear, logistics, and locations for international shoots.', 'إنتاج على أرض الواقع في مصر', 'تصاريح وطاقم ومعدات ولوجستيات ومواقع للتصوير الدولي.'],
            ['⚡', 'Disciplined Execution', 'Predictable budgets, premium results, and clear next steps.', 'تنفيذ منضبط', 'ميزانيات متوقعة، نتائج متميزة، وخطوات واضحة.'],
        ];

        foreach ($servicesWhy as $i => [$icon, $enTitle, $enDesc, $arTitle, $arDesc]) {
            $item = FeatureHighlight::query()->create(['context' => 'services', 'icon' => $icon, 'sort_order' => $i + 1]);
            $this->seedTranslations($item, ['title' => $enTitle, 'description' => $enDesc], ['title' => $arTitle, 'description' => $arDesc]);
        }

        $contactWhy = [
            ['⚡', '1-Hour Response', 'Our team is ready to answer your inquiries within one hour on business days', 'رد خلال ساعة', 'فريقنا جاهز للرد على استفساراتك خلال ساعة واحدة في أيام العمل'],
            ['🎯', 'Free Consultation', 'Get a free 30-minute marketing consultation with one of our experts', 'استشارة مجانية', 'احصل على استشارة تسويقية مجانية مدتها 30 دقيقة مع أحد خبرائنا'],
            ['🔒', 'Full Confidentiality', 'Your information and project are fully protected under the highest privacy standards', 'سرية تامة', 'معلوماتك ومشروعك محمي بالكامل وفق أعلى معايير الخصوصية'],
            ['🌟', '9+ Years Experience', 'A team of specialists in all areas of digital marketing', 'خبرة تزيد عن 9 سنوات', 'فريق من الخبراء المتخصصين في كل مجالات التسويق الرقمي'],
        ];

        foreach ($contactWhy as $i => [$icon, $enTitle, $enDesc, $arTitle, $arDesc]) {
            $item = FeatureHighlight::query()->create(['context' => 'contact', 'icon' => $icon, 'sort_order' => $i + 1]);
            $this->seedTranslations($item, ['title' => $enTitle, 'description' => $enDesc], ['title' => $arTitle, 'description' => $arDesc]);
        }
    }

    private function seedOffices(): void
    {
        Office::query()->delete();

        $offices = [
            [
                'flag' => '🇪🇬', 'city' => 'Egypt', 'country' => 'Egypt',
                'address' => 'Egyptian Media Production City, Egypt',
                'phone' => '+201001299639', 'email' => 'bendary@globaluntoldstory.com',
                'timezone' => 'GMT+2', 'is_headquarters' => true,
                'en' => ['title' => 'Egypt Office', 'status' => 'Main Office'],
                'ar' => ['title' => 'مكتب مصر', 'status' => 'المقر الرئيسي'],
            ],
            [
                'flag' => '🇦🇪', 'city' => 'Dubai', 'country' => 'UAE',
                'address' => 'Business Bay, Dubai, UAE',
                'phone' => '+971547711772', 'email' => null,
                'timezone' => 'GMT+4', 'is_headquarters' => false,
                'en' => ['title' => 'UAE Office', 'status' => 'Regional Office'],
                'ar' => ['title' => 'مكتب الإمارات', 'status' => 'مكتب إقليمي'],
            ],
        ];

        foreach ($offices as $i => $office) {
            $en = $office['en'];
            $ar = $office['ar'];
            unset($office['en'], $office['ar']);
            $model = Office::query()->create(array_merge($office, ['sort_order' => $i + 1]));
            $this->seedTranslations($model, $en, $ar);
        }
    }

    private function seedPartnerLabels(): void
    {
        PartnerLabel::query()->delete();

        $labels = [
            ['Brands', 'العلامات التجارية'],
            ['Platforms', 'المنصات'],
            ['Broadcasters', 'القنوات'],
            ['Institutions', 'المؤسسات'],
            ['International Production', 'الإنتاج الدولي'],
        ];

        foreach ($labels as $i => [$en, $ar]) {
            $label = PartnerLabel::query()->create(['sort_order' => $i + 1]);
            $this->seedTranslations($label, ['label' => $en], ['label' => $ar]);
        }
    }

    private function seedClientLogos(): void
    {
        ClientLogo::query()->delete();

        foreach (['Google Partner', 'Meta Business', 'TikTok For Business', 'HubSpot', 'Adobe', 'Semrush', 'Shopify'] as $i => $name) {
            $logo = ClientLogo::query()->create(['name' => $name, 'sort_order' => $i + 1, 'is_active' => true]);
            $this->seedTranslations($logo, ['display_name' => $name], ['display_name' => $name]);
        }
    }

    private function seedResources(): void
    {
        Resource::query()->delete();

        $resources = [
            ['📖', '#6366f1', 'Digital Marketing Guide 2024', 'Free PDF', 'دليل التسويق الرقمي 2024', 'PDF مجاني'],
            ['📊', '#10b981', 'Monthly Performance Report Template', 'Free Excel', 'قالب تقرير الأداء الشهري', 'Excel مجاني'],
            ['🎯', '#f59e0b', 'Essential Marketing Terms Glossary', 'Free Dictionary', 'مصطلحات التسويق الأساسية', 'قاموس مجاني'],
            ['🎬', '#8b5cf6', 'Social Media Workshop', 'Free Video', 'ورشة عمل السوشيال ميديا', 'فيديو مجاني'],
        ];

        foreach ($resources as $i => [$icon, $color, $enTitle, $enType, $arTitle, $arType]) {
            $resource = Resource::query()->create([
                'icon' => $icon,
                'color' => $color,
                'sort_order' => $i + 1,
                'file_url' => null,
            ]);
            $this->seedTranslations($resource, ['title' => $enTitle, 'type_label' => $enType], ['title' => $arTitle, 'type_label' => $arType]);
        }
    }

    private function seedAwards(): void
    {
        Award::query()->delete();

        $awards = [
            ['🎬', '#f59e0b', 'Film & Video', 'Full production cycle', 'MENA', 'أفلام وفيديو', 'دورة إنتاج كاملة', 'MENA'],
            ['📺', '#6366f1', 'Commercial Production', 'Advertising & branded content', 'Global', 'إنتاج تجاري', 'إعلانات ومحتوى العلامات', 'Global'],
            ['🌍', '#10b981', 'On-Ground Production', 'Egypt, Dubai & Jeddah', '3+ Offices', 'إنتاج على أرض الواقع', 'مصر ودبي وجدة', '3+ مكاتب'],
            ['✨', '#ef4444', 'Original IP', 'Stories designed to travel', 'In-house', 'ملكية فكرية أصلية', 'قصص مصممة للسفر', 'داخلي'],
        ];

        foreach ($awards as $i => [$icon, $color, $enTitle, $enOrg, $enYear, $arTitle, $arOrg, $arYear]) {
            $award = Award::query()->create(['icon' => $icon, 'color' => $color, 'sort_order' => $i + 1]);
            $this->seedTranslations($award, [
                'title' => $enTitle,
                'organization' => $enOrg,
                'year_label' => $enYear,
            ], [
                'title' => $arTitle,
                'organization' => $arOrg,
                'year_label' => $arYear,
            ]);
        }
    }

    private function seedSeoMeta(): void
    {
        SeoMeta::query()->delete();

        $pages = Page::query()->get();
        foreach ($pages as $page) {
            $translation = $page->translate('en');
            $translationAr = $page->translate('ar');
            if (! $translation) {
                continue;
            }

            $seo = SeoMeta::query()->create([
                'seoable_type' => Page::class,
                'seoable_id' => $page->id,
                'page_slug' => $page->slug,
                'robots' => 'index,follow',
            ]);

            $this->seedTranslations($seo, [
                'meta_title' => $translation->title.' | The Untold Story',
                'meta_description' => $translation->subtitle ?? $translation->title,
                'og_title' => $translation->title,
                'og_description' => $translation->subtitle,
            ], [
                'meta_title' => ($translationAr->title ?? $translation->title).' | The Untold Story',
                'meta_description' => $translationAr->subtitle ?? $translationAr->title,
                'og_title' => $translationAr->title,
                'og_description' => $translationAr->subtitle,
            ]);
        }
    }
}
