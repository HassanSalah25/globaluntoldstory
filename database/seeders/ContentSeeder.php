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
use App\Support\SeederTranslations;
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
        // $this->seedServices();
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
        SeederTranslations::seed($model, SeederTranslations::fromEnAr($en, $ar));
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
            SeederTranslations::seedSetting(
                $setting,
                SeederTranslations::scalarMap($values['en'], $values['ar'])
            );
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
            SeederTranslations::seedSetting($setting, SeederTranslations::scalarMap($en, $ar));
        }

        $this->seedSectionUiSettings();
    }

    private function seedSectionUiSettings(): void
    {
        $sectionKeys = [
            'sections.portfolio_title' => ['Projects done by The Untold Story', 'مشاريع من إنجاز The Untold Story'],
            'sections.quote_badge' => ['Where story meets execution', 'حيث تلتقي القصة بالتنفيذ'],
            'sections.quote_title' => ['Predictable budgets. Premium results.', 'ميزانيات متوقعة. نتائج متميزة.'],
            'sections.work_showcase_badge' => ['✨ Our Work', '✨ أحدث أعمالنا'],
            'sections.work_showcase_title' => ['Projects done by The Untold Story', 'إبداع يتجاوز التوقعات'],
            'sections.work_showcase_subtitle' => ['Film, video, advertising, documentaries, and corporate content', 'نماذج من مشاريعنا الناجحة في مختلف المجالات'],
            'sections.work_showcase_view_all' => ['Our Work', 'عرض جميع الأعمال'],
            'sections.process_badge' => ['Production Cycle', 'دورة الإنتاج'],
            'sections.process_title' => ['The Untold Story delivers the full production cycle', 'The Untold Story تقدّم دورة الإنتاج الكاملة'],
            'sections.testimonials_badge' => ['Creative Creations', 'Creative Creations'],
            'sections.testimonials_title' => ['Behind every Frame', 'Behind every Frame'],
            'sections.blog_badge' => ['News & Insights', 'المدونة'],
            'sections.blog_title' => ['News & Insights', 'آخر الأخبار والرؤى التسويقية'],
            'sections.blog_subtext' => ['Company news and updates', 'تابع أحدث المقالات والإلهامات حول الإعلان الرقمي، التسويق، والهوية البصرية.'],
            'sections.faq_badge' => ['FAQ', 'الأسئلة الشائعة'],
            'sections.faq_title' => ['Answers to Your Key Questions', 'أجوبة لأهم أسئلتك'],
            'sections.about_values_badge' => ['What We Offer', 'ما الذي نقدمه'],
            'sections.about_values_title' => ['End-to-End Production Excellence', 'إنتاج شامل بإتقان'],
            'sections.about_team_badge' => ['Leadership', 'القيادة'],
            'sections.about_team_title' => ['Leading The Untold Story', 'قيادة The Untold Story'],
            'sections.about_partners_badge' => ['Trusted By Industry Titans', 'موثوق من قبل عمالقة الصناعة'],
            'sections.about_partners_title' => ['Trusted By Industry Titans', 'موثوق من قبل عمالقة الصناعة'],
            'sections.services_grid_badge' => ['Our Services', 'خدماتنا'],
            'sections.services_grid_title' => ['Choose a service below', 'اختر خدمة أدناه'],
            'sections.services_quote_subtext' => ['Send your brief and we will respond with the right plan and next steps.', 'أرسل brief وسنرد بالخطة المناسبة والخطوات التالية.'],
            'sections.services_cta_sub' => ['Send your brief and we will respond with the right plan and next steps.', 'أرسل brief وسنرد بالخطة المناسبة والخطوات التالية.'],
            'sections.contact_info_title' => ['Contact Info', 'معلومات الاتصال'],
            'sections.contact_offices_title' => ['Our Offices', 'مكاتبنا'],
            'sections.contact_offices_subtext' => ['We are present in Egypt and the UAE to always be close to you', 'نحن موجودون في مصر والإمارات لنكون دائماً قريبين منك'],
            'sections.contact_form_title' => ['Send a Message', 'أرسل رسالة'],
            'sections.contact_label_name' => ['Full Name *', 'الاسم الكامل *'],
            'sections.contact_label_email' => ['Email Address *', 'البريد الإلكتروني *'],
            'sections.contact_label_phone' => ['Phone Number', 'رقم الهاتف'],
            'sections.contact_label_service' => ['Requested Service', 'الخدمة المطلوبة'],
            'sections.contact_label_message' => ['Your Message *', 'رسالتك *'],
            'sections.contact_label_choose_service' => ['Choose a Service', 'اختر الخدمة'],
            'sections.contact_quote_email_label' => ['Email', 'البريد الإلكتروني'],
            'sections.contact_quote_phone_label' => ['Phone and WhatsApp', 'الهاتف وWhatsApp'],
            'footer.about_title' => ['About', 'عن الشركة'],
            'footer.services_title' => ['More Pages', 'صفحات أخرى'],
            'footer.quick_links' => ['Quick Links', 'روابط سريعة'],
            'footer.email_label' => ['Email', 'البريد الإلكتروني'],
            'footer.all_rights' => ['All rights reserved.', 'جميع الحقوق محفوظة.'],
            'footer.admin_dashboard' => ['Admin Dashboard →', 'لوحة تحكم الإدارة ←'],
        ];

        foreach ($sectionKeys as $key => [$en, $ar]) {
            $group = str_starts_with($key, 'footer.') ? 'footer' : 'sections';
            $setting = Setting::query()->updateOrCreate(
                ['key' => $key],
                ['group' => $group, 'value' => null]
            );
            SeederTranslations::seedSetting($setting, SeederTranslations::scalarMap($en, $ar));
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
                'en' => [
                    'title' => 'The Untold Story Film Production in Egypt',
                    'subtitle' => 'The Untold Story production team at the Pyramids of Giza, representing expertise in film, video, and on-ground production services across Egypt.',
                    'badge' => 'About Us',
                ],
                'ar' => [
                    'title' => 'إنتاج أفلام The Untold Story في مصر',
                    'subtitle' => 'فريق إنتاج The Untold Story عند أهرامات الجيزة، يمثل خبرة في الإنتاج السينمائي والفيديو والخدمات الميدانية في جميع أنحاء مصر.',
                    'badge' => 'من نحن',
                ],
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
                'en' => [
                    'title' => 'News & Insights',
                    'subtitle' => 'Insights on professional film production, commercial advertising, documentaries, branded content, and cinematic storytelling from The Untold Story.',
                    'badge' => 'News & Insights',
                ],
                'ar' => [
                    'title' => 'آخر الأخبار والرؤى',
                    'subtitle' => 'رؤى حول الإنتاج السينمائي الاحترافي والإعلانات التجارية والأفلام الوثائقية والمحتوى المرتبط بالعلامات والسرد السينمائي من The Untold Story.',
                    'badge' => 'المدونة',
                ],
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
                $heroSplit = PageSection::query()->create([
                    'page_id' => $page->id,
                    'type' => 'hero_split',
                    'sort_order' => 0,
                    'settings' => [
                        'image' => $this->media('hero-slide-1'),
                        'production_pipeline' => ['Planning', 'Filming', 'Live', 'Post & final delivery', 'Localization'],
                        'headline_suffix_en' => 'production services in Egypt',
                        'headline_suffix_ar' => 'خدمات الإنتاج في مصر',
                        'cta_secondary_label_en' => 'Our Work',
                        'cta_secondary_label_ar' => 'أعمالنا',
                        'cta_secondary_url' => '/portfolio',
                    ],
                    'is_active' => true,
                ]);
                $this->seedTranslations($heroSplit, [
                    'badge' => 'On-Ground Production Services in Egypt',
                    'title' => 'The Untold Story delivers',
                    'subtitle' => 'on-ground',
                    'content' => 'The Untold Story delivers professional on-ground production services in Egypt, including filming permits, location management, production logistics, local crew support, and full-service film production at iconic locations such as the Giza Pyramids.',
                    'cta_label' => 'Contact us for Next project',
                    'cta_url' => '/contact',
                ], [
                    'badge' => 'خدمات الإنتاج الميداني في مصر',
                    'title' => 'تقدّم The Untold Story',
                    'subtitle' => 'إنتاجاً ميدانياً',
                    'content' => 'تقدّم The Untold Story خدمات إنتاج ميداني احترافية في مصر، تشمل تصاريح التصوير، وإدارة المواقع، ولوجستيات الإنتاج، ودعم الطاقم المحلي، وإنتاج أفلام متكامل في مواقع أيقونية مثل أهرامات الجيزة.',
                    'cta_label' => 'تواصل معنا للمشروع القادم',
                    'cta_url' => '/contact',
                ]);

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

                $photography = PageSection::query()->create([
                    'page_id' => $page->id,
                    'type' => 'photography',
                    'sort_order' => 3,
                    'settings' => ['image' => $this->media('home-photography-section')],
                    'is_active' => true,
                ]);
                $this->seedTranslations($photography, [
                    'badge' => 'Photography',
                    'title' => 'Professional Cinema Camera Equipment',
                    'content' => 'Professional ARRI Alexa Mini LF cinema camera and filmmaking equipment used for commercial productions, documentaries, branded content, and film projects in Egypt.',
                    'subtitle' => 'Polished and aligned with your tone',
                    'cta_label' => 'Contact Us',
                    'cta_url' => '/contact',
                ], [
                    'badge' => 'التصوير',
                    'title' => 'معدات الكاميرات السينمائية الاحترافية',
                    'content' => 'كاميرا ARRI Alexa Mini LF السينمائية ومعدات التصوير السينمائي المستخدمة في الإنتاجات التجارية والأفلام الوثائقية والمحتوى المرتبط بالعلامات ومشاريع الأفلام في مصر.',
                    'subtitle' => 'مصقول ومتناسق مع أسلوبك',
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
                    'badge' => null,
                    'title' => 'On-Ground Production Services in Egypt',
                    'title_highlight' => null,
                    'subtitle' => 'Get a quote in 24h',
                    'description' => 'The Untold Story delivers professional on-ground production services in Egypt, including filming permits, location management, production logistics, local crew support, and full-service film production at iconic locations such as the Giza Pyramids.',
                    'cta_primary_label' => 'Contact us for Next project',
                    'cta_primary_url' => '/contact',
                    'cta_secondary_label' => 'Our Work',
                    'cta_secondary_url' => '/portfolio',
                ],
                'ar' => [
                    'badge' => null,
                    'title' => 'خدمات الإنتاج الميداني في مصر',
                    'title_highlight' => null,
                    'subtitle' => 'احصل على عرض سعر خلال 24 ساعة',
                    'description' => 'تقدّم The Untold Story خدمات إنتاج ميداني احترافية في مصر، تشمل تصاريح التصوير، وإدارة المواقع، ولوجستيات الإنتاج، ودعم الطاقم المحلي، وإنتاج أفلام متكامل في مواقع أيقونية مثل أهرامات الجيزة.',
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
                    'badge' => null,
                    'title' => 'Film Production Services in Egypt',
                    'title_highlight' => null,
                    'subtitle' => 'Our Storytelling & Production Expertise',
                    'description' => 'Professional film production services in Egypt by The Untold Story, offering location scouting, production support, crew management, filming permits, and cinematic storytelling across Egypt\'s most iconic heritage locations.',
                    'cta_primary_label' => 'Contact Us',
                    'cta_primary_url' => '/contact',
                    'cta_secondary_label' => 'Our Work',
                    'cta_secondary_url' => '/portfolio',
                ],
                'ar' => [
                    'badge' => null,
                    'title' => 'خدمات إنتاج الأفلام في مصر',
                    'title_highlight' => null,
                    'subtitle' => 'خبرتنا في السرد والإنتاج',
                    'description' => 'خدمات إنتاج أفلام احترافية في مصر من The Untold Story، تشمل استكشاف المواقع، ودعم الإنتاج، وإدارة الطاقم، وتصاريح التصوير، والسرد السينمائي في أشهر المواقع التراثية في مصر.',
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

        // [slug, icon, featured_image_key, all_image_key, enTitle, arTitle, enDesc, arDesc]
        // The first 4 entries are the featured service cards (800×540 images)
        $services = [
            ['on-ground-egypt', '🇪🇬', 'svc-on-ground', 'svc-all-on-ground', 'On Ground Production Services Egypt', 'خدمات الإنتاج الميداني في مصر', 'On-ground production services in Egypt including logistics, permits, location scouting, and crew support.', 'خدمات إنتاج ميداني في مصر تشمل اللوجستيات والتصاريح واستكشاف المواقع ودعم الطاقم.'],
            ['commercial', '📺', 'svc-commercial', 'svc-all-commercial', 'Commercial Advertising Production', 'إنتاج الإعلانات التجارية', 'Professional commercial advertising production services creating engaging brand campaigns, television commercials, and digital marketing content.', 'خدمات إنتاج إعلانات تجارية احترافية لحملات العلامات والإعلانات التلفزيونية والمحتوى الرقمي.'],
            ['documentary', '🎬', 'svc-documentary', 'svc-all-documentary', 'Documentary Production Services', 'إنتاج الأفلام الوثائقية', 'Professional documentary production services in Egypt focused on cultural heritage, history, tourism, and factual storytelling.', 'خدمات إنتاج وثائقي احترافية في مصر تركز على التراث الثقافي والتاريخ والسياحة والسرد الواقعي.'],
            ['corporate', '🏢', 'svc-corporate', 'svc-all-corporate', 'Corporate and Industrial Content', 'المحتوى المؤسسي والصناعي', 'Corporate and industrial content production for manufacturing, infrastructure, energy, engineering, and enterprise sectors.', 'إنتاج محتوى مؤسسي وصناعي لقطاعات التصنيع والبنية التحتية والطاقة والهندسة والمؤسسات.'],
            ['events', '🎤', null, 'svc-all-events', 'Event Coverage and Live Production', 'تغطية الفعاليات والإنتاج المباشر', 'Live event coverage and production services including broadcasting, streaming, switching, and real-time content delivery.', 'خدمات تغطية وإنتاج الفعاليات المباشرة تشمل البث والبث المباشر والتبديل وتسليم المحتوى في الوقت الفعلي.'],
            ['tv-broadcast', '📡', null, 'svc-all-tv-broadcast', 'TV Shows and Live Broadcast Production', 'إنتاج البرامج التلفزيونية والبث المباشر', 'TV show production and live broadcasting services for television, streaming platforms, and media networks.', 'إنتاج البرامج التلفزيونية وخدمات البث المباشر للتلفزيون ومنصات البث وشبكات الإعلام.'],
            ['podcast', '🎙️', null, 'svc-all-podcast', 'Podcast Production Services', 'خدمات إنتاج البودكاست', 'Podcast production services including recording, editing, audio enhancement, and content distribution.', 'خدمات إنتاج البودكاست تشمل التسجيل والمونتاج وتحسين الصوت وتوزيع المحتوى.'],
            ['post-production', '✂️', null, 'svc-all-post-production', 'Post Production and Finishing', 'ما بعد الإنتاج والإنهاء', 'Post-production services including editing, color grading, visual enhancement, sound design, and finishing.', 'خدمات ما بعد الإنتاج تشمل المونتاج وتصحيح الألوان وتحسين المرئيات وتصميم الصوت والإنهاء.'],
            ['motion-cgi', '✨', null, 'svc-all-motion-cgi', 'Motion CGI and AI Powered Visuals', 'الموشن والـ CGI والمرئيات بالذكاء الاصطناعي', 'AI-powered visuals, CGI, motion graphics, virtual production, and digital content creation services.', 'مرئيات مدعومة بالذكاء الاصطناعي وCGI وموشن جرافيك وإنتاج افتراضي وخدمات إنشاء محتوى رقمي.'],
            ['dubbing', '🌐', null, 'svc-all-dubbing', 'Voice Over and Localization Services', 'التعليق الصوتي والتعريب', 'Dubbing, voice-over recording, and content localization services.', 'خدمات الدبلجة والتعليق الصوتي وتعريب المحتوى.'],
            ['photography', '📷', null, 'svc-all-photography', 'Professional Photography Services', 'خدمات التصوير الاحترافي', 'Commercial, corporate, product, and event photography services using professional imaging equipment.', 'خدمات تصوير تجاري ومؤسسي ومنتجات وفعاليات باستخدام معدات تصوير احترافية.'],
            ['marketing', '📊', null, 'svc-all-marketing', 'Marketing Solutions and Performance', 'حلول التسويق والأداء', 'Marketing solutions and performance optimization services focused on analytics, strategy, lead generation, and business growth.', 'حلول تسويق وتحسين أداء تركز على التحليلات والاستراتيجية وتوليد العملاء المحتملين ونمو الأعمال.'],
            ['original-ip', '💡', null, 'svc-all-original-ip', 'Original IP Development and Creative Concept Creation', 'تطوير الملكية الفكرية الأصلية', 'Original IP development services focused on creating unique characters, story concepts, branded entertainment properties, and original creative assets for film, television, digital media, and commercial productions.', 'خدمات تطوير ملكية فكرية أصلية تركز على إنشاء شخصيات فريدة ومفاهيم قصصية وخصائص ترفيهية للعلامات وأصول إبداعية أصلية للأفلام والتلفزيون والإعلام الرقمي والإنتاج التجاري.'],
        ];

        foreach ($services as $i => [$slug, $icon, $featuredImgKey, $allImgKey, $enTitle, $arTitle, $enDesc, $arDesc]) {
            $service = Service::query()->create([
                'slug' => $slug,
                'icon' => $icon,
                'image_url' => $this->media($allImgKey),
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

        $groups = [
            'commercial-advertising' => [
                'category' => 'digital-ads',
                'media' => ['digital-marketing', 'fashion-retail', 'gaming-video'],
            ],
            'documentary' => [
                'category' => 'video',
                'media' => ['film-production', 'cinema-camera', 'camera-equipment'],
            ],
            'industry' => [
                'category' => 'video',
                'media' => ['analytics-dashboard', 'executive-portrait', 'camera-equipment', 'film-production', 'portrait-man-1', 'portrait-man-2', 'cinema-camera'],
            ],
            'tv-show-live' => [
                'category' => 'video',
                'media' => ['gaming-video', 'gaming-arena'],
            ],
        ];

        $sortOrder = 0;

        foreach ($groups as $portfolioSlug => $config) {
            $data = $this->loadStructuredPortfolio($portfolioSlug, 'en');

            foreach ($data['items'] ?? [] as $index => $item) {
                if (! is_array($item)) {
                    continue;
                }

                $sortOrder++;
                $description = $item['description'] ?? [];
                $resultsText = is_array($description) ? implode("\n", $description) : (string) $description;
                $resultsShort = is_array($description) && isset($description[0])
                    ? \Illuminate\Support\Str::limit($description[0], 240)
                    : \Illuminate\Support\Str::limit($resultsText, 240);
                $metric = $item['service'] ?? $item['client'] ?? 'Production';
                $mediaKeys = $config['media'];
                $mediaKey = $mediaKeys[$index % count($mediaKeys)];

                $model = PortfolioItem::query()->create([
                    'slug' => $portfolioSlug.'-'.$index,
                    'category_id' => $categories['portfolio'][$config['category']] ?? null,
                    'client_name' => $item['client'] ?? '',
                    'image_url' => $this->media($mediaKey),
                    'duration' => $item['industry'] ?? null,
                    'budget' => $item['service'] ?? null,
                    'results' => $resultsShort,
                    'metric' => $metric,
                    'sort_order' => $sortOrder,
                    'is_featured' => $index === 0,
                    'is_active' => true,
                    'grid_size' => ($index === 0 && $sortOrder <= 4) ? 'large' : 'small',
                ]);

                $this->seedTranslations($model, [
                    'title' => $item['title'] ?? '',
                    'results_text' => $resultsText,
                    'metric' => $metric,
                ], [
                    'title' => $item['title'] ?? '',
                    'results_text' => $resultsText,
                    'metric' => $metric,
                ]);
            }
        }
    }

    private function seedBlogPosts(array $categories): void
    {
        BlogPost::query()->delete();

        $featured = BlogPost::query()->create([
            'slug' => 'professional-film-production-equipment',
            'category_id' => $categories['blog']['content'] ?? null,
            'author_name' => 'The Untold Story',
            'author_image_url' => $this->media('executive-portrait'),
            'featured_image_url' => $this->media('blog-film-production'),
            'published_at' => Carbon::parse('2026-06-01'),
            'read_time_minutes' => 6,
            'is_featured' => true,
            'is_published' => true,
            'sort_order' => 0,
        ]);
        $this->seedTranslations($featured, [
            'title' => 'Professional Film Production Equipment',
            'excerpt' => 'High-end RED Dragon cinema camera used in professional film production, commercial advertising, documentaries, branded content, and cinematic storytelling projects by The Untold Story.',
            'body' => null,
            'tags' => ['Film Production', 'Cinema Camera', 'RED Dragon'],
        ], [
            'title' => 'معدات الإنتاج السينمائي الاحترافية',
            'excerpt' => 'كاميرا RED Dragon السينمائية عالية الجودة المستخدمة في الإنتاج السينمائي الاحترافي والإعلانات التجارية والأفلام الوثائقية والمحتوى المرتبط بالعلامات ومشاريع السرد السينمائي من The Untold Story.',
            'body' => null,
            'tags' => ['إنتاج سينمائي', 'كاميرا سينمائية', 'RED Dragon'],
        ]);

        $structuredArticles = [
            ['how-to-choose-a-media-production-agency-in-egypt', 'content', '2026-06-20', 8],
            ['the-video-production-journey-from-idea-to-impact', 'content', '2026-06-15', 6],
            ['why-every-brand-needs-a-story-that-moves-people', 'content', '2026-06-10', 5],
        ];

        foreach ($structuredArticles as $i => [$slug, $cat, $date, $readTime]) {
            $article = $this->loadStructuredArticle($slug, 'en');
            $sections = $article['sections'] ?? [];
            $body = $this->sectionsToHtml([], $sections);
            $title = $this->resolveArticleTitle($slug, (string) ($article['title'] ?? ''), $sections);
            $excerpt = $this->firstParagraphFromSections($sections) ?? \Illuminate\Support\Str::limit(strip_tags($body), 240);

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
                'title' => $title,
                'excerpt' => $excerpt,
                'body' => $body,
                'tags' => ['Production', 'Storytelling'],
            ], [
                'title' => $title,
                'excerpt' => $excerpt,
                'body' => $body,
                'tags' => ['إنتاج', 'سرد'],
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

    private function loadStructuredArticle(string $slug, string $locale): array
    {
        $path = database_path("structured_content/content/articles/{$slug}/{$locale}.json");
        if (! is_file($path)) {
            return [];
        }

        return json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
    }

    private function loadStructuredPortfolio(string $slug, string $locale): array
    {
        $path = database_path("structured_content/content/portfolios/{$slug}/{$locale}.json");
        if (! is_file($path)) {
            return [];
        }

        return json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
    }

    private function resolveArticleTitle(string $slug, string $rawTitle, array $sections): string
    {
        if ($rawTitle !== '' && strlen($rawTitle) <= 200 && str_word_count($rawTitle) <= 16) {
            return $rawTitle;
        }

        foreach ($sections as $section) {
            $heading = $section['heading'] ?? null;
            if (is_string($heading) && $heading !== '') {
                return \Illuminate\Support\Str::limit($heading, 200, '');
            }
        }

        if ($rawTitle !== '') {
            return \Illuminate\Support\Str::limit($rawTitle, 200, '…');
        }

        return \Illuminate\Support\Str::limit(\Illuminate\Support\Str::headline(str_replace('-', ' ', $slug)), 200, '');
    }

    private function sectionsToHtml(array $paragraphs, array $sections): string
    {
        $html = '';
        foreach ($paragraphs as $paragraph) {
            if ($paragraph !== '') {
                $html .= '<p>'.e($paragraph).'</p>';
            }
        }
        foreach ($sections as $section) {
            $heading = $section['heading'] ?? null;
            if (is_string($heading) && $heading !== '') {
                $html .= '<h2>'.e($heading).'</h2>';
            }
            foreach ($section['body'] ?? [] as $paragraph) {
                if ($paragraph !== '') {
                    $html .= '<p>'.e($paragraph).'</p>';
                }
            }
        }

        return $html;
    }

    private function firstParagraphFromSections(array $sections): ?string
    {
        foreach ($sections as $section) {
            foreach ($section['body'] ?? [] as $paragraph) {
                if ($paragraph !== '') {
                    return $paragraph;
                }
            }
        }

        return null;
    }
}
