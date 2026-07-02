<?php

namespace App\Support;

use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\Setting;
use App\Models\Stat;
use Illuminate\Support\Facades\Cache;

class StructuredUiImporter
{
    private const MAIN_MENU_URLS = ['/', '/about', '/services', '/portfolio', '/blog', '/contact'];

    private const FOOTER_ABOUT_URLS = ['/about', '/portfolio', '/contact'];

    public function import(string $locale, string $contentRoot): void
    {
        $home = $this->loadJson($contentRoot, "content/pages/home-page/{$locale}.json");
        $about = $this->loadJson($contentRoot, "content/pages/about-us/{$locale}.json");
        $services = $this->loadJson($contentRoot, "content/pages/services-overview/{$locale}.json");
        $contact = $this->loadJson($contentRoot, "content/pages/contact-page/{$locale}.json");

        if ($home) {
            $this->importMainMenu($locale, $home);
            $this->importFooterMenu($locale, $home);
            $this->importHomeSettings($locale, $home);
            $this->importHomePageSections($locale, $home);
        }

        if ($about) {
            $this->importHomeStats($locale, $about, $home);
            $this->importAboutSettings($locale, $about);
        }

        if ($services) {
            $this->importServicesPageSettings($locale, $services);
        }

        if ($contact) {
            $this->importContactPageSettings($locale, $contact);
        }

        Cache::forget('cms.settings');
    }

    private function loadJson(string $contentRoot, string $relativePath): ?array
    {
        $path = $contentRoot.'/'.ltrim(str_replace('\\', '/', $relativePath), '/');
        if (! is_file($path)) {
            return null;
        }

        return json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
    }

    private function importMainMenu(string $locale, array $home): void
    {
        $menu = Menu::query()->where('slug', 'main')->first();
        if (! $menu) {
            return;
        }

        $items = $menu->items()->orderBy('sort_order')->get()->keyBy('url');

        foreach (self::MAIN_MENU_URLS as $index => $url) {
            $label = $this->heading($home, $index);
            $item = $items->get($url);

            if ($label && $item) {
                $item->translations()->updateOrCreate(
                    ['locale' => $locale],
                    ['label' => $label]
                );
            }
        }
    }

    private function importFooterMenu(string $locale, array $home): void
    {
        $menu = Menu::query()->where('slug', 'footer-about')->first();
        if (! $menu) {
            return;
        }

        $items = $menu->items()->orderBy('sort_order')->get()->keyBy('url');
        $labels = [
            '/about' => $this->heading($home, 32) ?? $this->heading($home, 31),
            '/portfolio' => $this->bodyLine($home, 32, 0),
            '/contact' => $this->bodyLine($home, 32, 1),
        ];

        foreach (self::FOOTER_ABOUT_URLS as $url) {
            $label = $labels[$url] ?? null;
            $item = $items->get($url);

            if ($label && $item) {
                $item->translations()->updateOrCreate(
                    ['locale' => $locale],
                    ['label' => $label]
                );
            }
        }
    }

    private function importHomeSettings(string $locale, array $home): void
    {
        $announcement = collect([
            $this->heading($home, 7),
            $this->heading($home, 6),
        ])->filter()->implode(' — ');

        $pipeline = $this->parsePipelineHeading($this->heading($home, 9));

        $this->upsertSettingTranslation($locale, 'announcement.text', 'banner', $announcement);
        $this->upsertSettingTranslation($locale, 'site.tagline', 'general', $this->bodyLine($home, 5, 0));
        $this->upsertSettingTranslation($locale, 'sections.portfolio_title', 'sections', $this->heading($home, 10));
        $this->upsertSettingTranslation($locale, 'sections.quote_badge', 'sections', $this->heading($home, 21));
        $this->upsertSettingTranslation($locale, 'sections.quote_title', 'sections', $this->bodyLine($home, 21, 0));
        $this->upsertSettingTranslation($locale, 'sections.work_showcase_badge', 'sections', '✨ '.($this->heading($home, 10) ?? ''));
        $this->upsertSettingTranslation($locale, 'sections.work_showcase_title', 'sections', $this->heading($home, 10));
        $this->upsertSettingTranslation($locale, 'sections.work_showcase_subtitle', 'sections', $this->bodyLine($home, 8, 0));
        $this->upsertSettingTranslation($locale, 'sections.work_showcase_view_all', 'sections', $this->bodyLine($home, 5, 1) ?? $this->heading($home, 3));
        $this->upsertSettingTranslation($locale, 'sections.process_badge', 'sections', $this->pipelineBadge($pipeline));
        $this->upsertSettingTranslation($locale, 'sections.process_title', 'sections', $this->heading($home, 8));
        $this->upsertSettingTranslation($locale, 'sections.testimonials_badge', 'sections', $this->heading($home, 27));
        $this->upsertSettingTranslation($locale, 'sections.testimonials_title', 'sections', $this->heading($home, 28));
        $this->upsertSettingTranslation($locale, 'sections.blog_badge', 'sections', $this->heading($home, 29));
        $this->upsertSettingTranslation($locale, 'sections.blog_title', 'sections', $this->heading($home, 29));
        $this->upsertSettingTranslation($locale, 'sections.blog_subtext', 'sections', $this->heading($home, 30));
        $this->upsertSettingTranslation($locale, 'footer.about_title', 'footer', $this->heading($home, 31));
        $this->upsertSettingTranslation($locale, 'footer.services_title', 'footer', $this->heading($home, 33));
        $this->upsertSettingTranslation($locale, 'footer.email_label', 'footer', $this->heading($home, 34));
        $this->upsertSettingTranslation($locale, 'footer.all_rights', 'footer', $this->defaultAllRights($locale));
        $this->upsertSettingTranslation($locale, 'footer.admin_dashboard', 'footer', $this->defaultAdminDashboard($locale));
        $this->upsertSettingTranslation($locale, 'footer.quick_links', 'footer', $this->defaultQuickLinks($locale));
    }

    private function importHomePageSections(string $locale, array $home): void
    {
        $page = Page::query()->where('slug', 'home')->first();
        if (! $page) {
            return;
        }

        $sections = $page->sections()->with('translations')->get()->keyBy('type');
        $pipeline = $this->parsePipelineHeading($this->heading($home, 9));

        if ($servicesIntro = $sections->get('services_intro')) {
            $servicesIntro->translations()->updateOrCreate(['locale' => $locale], [
                'badge' => $this->heading($home, 11),
                'title' => $this->heading($home, 11),
                'subtitle' => $this->bodyLine($home, 8, 0),
                'content' => $this->heading($home, 20),
            ]);

            $settings = $servicesIntro->settings ?? [];
            if ($pipeline !== []) {
                $settings["production_pipeline_{$locale}"] = $pipeline;
            }
            $servicesIntro->update(['settings' => $settings]);
        }

        if ($cta = $sections->get('cta_banner')) {
            $cta->translations()->updateOrCreate(['locale' => $locale], [
                'title' => $this->bodyLine($home, 30, 0),
                'content' => collect([
                    $this->heading($home, 6),
                    $this->heading($home, 7),
                ])->filter()->implode(' — '),
                'cta_label' => $this->heading($home, 5),
                'cta_url' => '/contact',
            ]);
        }

        if ($photography = $sections->get('photography')) {
            $photography->translations()->updateOrCreate(['locale' => $locale], [
                'badge' => $this->heading($home, 19),
                'title' => $this->bodyLine($home, 24, 0) ?? $this->heading($home, 19),
                'content' => $this->bodyLine($home, 24, 1),
                'subtitle' => $this->heading($home, 26),
            ]);
        }

        if ($heroSplit = $sections->get('hero_split')) {
            $settings = $heroSplit->settings ?? [];
            if ($pipeline !== []) {
                $settings["production_pipeline_{$locale}"] = $pipeline;
            }
            $settings["cta_secondary_label_{$locale}"] = $this->bodyLine($home, 5, 1) ?? $settings["cta_secondary_label_{$locale}"] ?? null;
            $heroSplit->update(['settings' => $settings]);
        }
    }

    private function importHomeStats(string $locale, array $about, ?array $home): void
    {
        $stats = Stat::query()->where('context', 'home')->orderBy('sort_order')->get();
        if ($stats->isEmpty()) {
            return;
        }

        $combinedLine = $this->bodyLine($about, 2, 0);
        $labels = [
            $this->heading($about, 2),
            $this->extractBeforePercentClause($combinedLine),
            $this->extractPercentClause($combinedLine),
            $home ? $this->heading($home, 8) : null,
        ];

        foreach ($stats as $index => $stat) {
            $label = $labels[$index] ?? null;
            if (! $label) {
                continue;
            }

            $stat->translations()->updateOrCreate(
                ['locale' => $locale],
                ['label' => $label]
            );
        }

        $portfolioStats = Stat::query()->where('context', 'portfolio')->orderBy('sort_order')->get();
        $portfolioLabels = $this->defaultPortfolioStatLabels($locale);

        foreach ($portfolioStats as $index => $stat) {
            $label = $portfolioLabels[$index] ?? null;
            if (! $label) {
                continue;
            }

            $stat->translations()->updateOrCreate(
                ['locale' => $locale],
                ['label' => $label]
            );
        }
    }

    private function importAboutSettings(string $locale, array $about): void
    {
        $partnersBadge = collect([
            $this->heading($about, 4),
            $this->bodyLine($about, 4, 0),
        ])->filter()->implode(' ');

        $this->upsertSettingTranslation($locale, 'sections.about_values_badge', 'sections', $this->defaultAboutValuesBadge($locale));
        $this->upsertSettingTranslation($locale, 'sections.about_values_title', 'sections', $this->defaultAboutValuesTitle($locale));
        $this->upsertSettingTranslation($locale, 'sections.about_team_badge', 'sections', $this->defaultAboutTeamBadge($locale));
        $this->upsertSettingTranslation($locale, 'sections.about_team_title', 'sections', $this->defaultAboutTeamTitle($locale));
        $this->upsertSettingTranslation($locale, 'sections.about_partners_badge', 'sections', $partnersBadge ?: $this->defaultAboutPartnersBadge($locale));
        $this->upsertSettingTranslation($locale, 'sections.about_partners_title', 'sections', $partnersBadge ?: $this->defaultAboutPartnersTitle($locale));
    }

    private function importServicesPageSettings(string $locale, array $services): void
    {
        $this->upsertSettingTranslation($locale, 'sections.services_grid_badge', 'sections', $this->heading($services, 0));
        $this->upsertSettingTranslation($locale, 'sections.services_grid_title', 'sections', $this->bodyLine($services, 0, 0));
        $this->upsertSettingTranslation($locale, 'sections.services_quote_subtext', 'sections', $this->defaultServicesQuoteSubtext($locale));
        $this->upsertSettingTranslation($locale, 'sections.services_cta_sub', 'sections', $this->defaultServicesQuoteSubtext($locale));
    }

    private function importContactPageSettings(string $locale, array $contact): void
    {
        $this->upsertSettingTranslation($locale, 'sections.contact_info_title', 'sections', $this->defaultContactInfoTitle($locale));
        $this->upsertSettingTranslation($locale, 'sections.contact_offices_title', 'sections', $this->defaultContactOfficesTitle($locale));
        $this->upsertSettingTranslation($locale, 'sections.contact_offices_subtext', 'sections', $this->defaultContactOfficesSubtext($locale));
        $this->upsertSettingTranslation($locale, 'sections.contact_form_title', 'sections', $this->defaultContactFormTitle($locale));
        $this->upsertSettingTranslation($locale, 'sections.contact_label_name', 'sections', $this->defaultContactLabel($locale, 'name'));
        $this->upsertSettingTranslation($locale, 'sections.contact_label_email', 'sections', $this->defaultContactLabel($locale, 'email'));
        $this->upsertSettingTranslation($locale, 'sections.contact_label_phone', 'sections', $this->defaultContactLabel($locale, 'phone'));
        $this->upsertSettingTranslation($locale, 'sections.contact_label_service', 'sections', $this->defaultContactLabel($locale, 'service'));
        $this->upsertSettingTranslation($locale, 'sections.contact_label_message', 'sections', $this->defaultContactLabel($locale, 'message'));
        $this->upsertSettingTranslation($locale, 'sections.contact_label_choose_service', 'sections', $this->defaultContactLabel($locale, 'choose_service'));
        $this->upsertSettingTranslation($locale, 'sections.contact_quote_email_label', 'sections', $this->heading($contact, 0) === 'Büroadresse' ? 'E-Mail-Adresse' : $this->defaultContactQuoteEmailLabel($locale));
        $this->upsertSettingTranslation($locale, 'sections.contact_quote_phone_label', 'sections', $this->defaultContactQuotePhoneLabel($locale));
        $this->upsertSettingTranslation($locale, 'sections.faq_badge', 'sections', $this->defaultFaqBadge($locale));
        $this->upsertSettingTranslation($locale, 'sections.faq_title', 'sections', $this->defaultFaqTitle($locale));
    }

    private function upsertSettingTranslation(string $locale, string $key, string $group, ?string $value): void
    {
        if ($value === null || $value === '') {
            return;
        }

        $setting = Setting::query()->firstOrCreate(
            ['key' => $key],
            ['group' => $group, 'value' => null]
        );

        $setting->translations()->updateOrCreate(
            ['locale' => $locale],
            ['value' => $value]
        );
    }

    private function heading(array $data, int $index): ?string
    {
        $heading = $data['blocks'][$index]['heading'] ?? null;

        return is_string($heading) && $heading !== '' ? trim($heading) : null;
    }

    private function bodyLine(array $data, int $blockIndex, int $lineIndex = 0): ?string
    {
        $line = $data['blocks'][$blockIndex]['body'][$lineIndex] ?? null;

        return is_string($line) && $line !== '' ? trim($line) : null;
    }

    private function parsePipelineHeading(?string $heading): array
    {
        if ($heading === null || $heading === '') {
            return [];
        }

        $parts = preg_split('/\s*[•·|]\s*/u', $heading) ?: [];

        return array_values(array_filter(array_map('trim', $parts)));
    }

    private function pipelineBadge(array $pipeline): ?string
    {
        return $pipeline[0] ?? null;
    }

    private function extractBeforePercentClause(?string $line): ?string
    {
        if ($line === null) {
            return null;
        }

        if (preg_match('/^(.*?)(?:\s*(?:with|mit|con|avec|com|ile|с)\s+a?\s*\d+\s*%|\s*\d+\s*%)/iu', $line, $matches)) {
            return trim($matches[1], " \t.,;");
        }

        return $line;
    }

    private function extractPercentClause(?string $line): ?string
    {
        if ($line === null) {
            return null;
        }

        if (preg_match('/(\d+\s*%.+)$/iu', $line, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }

    private function defaultAllRights(string $locale): string
    {
        return match ($locale) {
            'de' => 'Alle Rechte vorbehalten.',
            'es' => 'Todos los derechos reservados.',
            'fr' => 'Tous droits réservés.',
            'it' => 'Tutti i diritti riservati.',
            'pt' => 'Todos os direitos reservados.',
            'tr' => 'Tüm hakları saklıdır.',
            'ru' => 'Все права защищены.',
            default => 'All rights reserved.',
        };
    }

    private function defaultAdminDashboard(string $locale): string
    {
        return match ($locale) {
            'de' => 'Admin-Dashboard →',
            'es' => 'Panel de administración →',
            'fr' => 'Tableau de bord admin →',
            'it' => 'Dashboard admin →',
            'pt' => 'Painel admin →',
            'tr' => 'Yönetici paneli →',
            'ru' => 'Панель администратора →',
            default => 'Admin Dashboard →',
        };
    }

    private function defaultQuickLinks(string $locale): string
    {
        return match ($locale) {
            'de' => 'Schnelllinks',
            'es' => 'Enlaces rápidos',
            'fr' => 'Liens rapides',
            'it' => 'Link rapidi',
            'pt' => 'Links rápidos',
            'tr' => 'Hızlı bağlantılar',
            'ru' => 'Быстрые ссылки',
            default => 'Quick Links',
        };
    }

    private function defaultPortfolioStatLabels(string $locale): array
    {
        return match ($locale) {
            'de' => ['Abgeschlossene Projekte', 'Zufriedene Kunden', 'Länder'],
            'es' => ['Proyectos realizados', 'Clientes satisfechos', 'Países'],
            'fr' => ['Projets réalisés', 'Clients satisfaits', 'Pays'],
            'it' => ['Progetti completati', 'Clienti soddisfatti', 'Paesi'],
            'pt' => ['Projetos concluídos', 'Clientes satisfeitos', 'Países'],
            'tr' => ['Tamamlanan projeler', 'Mutlu müşteriler', 'Ülkeler'],
            'ru' => ['Выполненные проекты', 'Довольные клиенты', 'Страны'],
            default => ['Projects Done', 'Happy Clients', 'Countries'],
        };
    }

    private function defaultAboutValuesBadge(string $locale): string
    {
        return match ($locale) {
            'de' => 'Was wir anbieten',
            'es' => 'Lo que ofrecemos',
            'fr' => 'Ce que nous offrons',
            'it' => 'Cosa offriamo',
            'pt' => 'O que oferecemos',
            'tr' => 'Neler sunuyoruz',
            'ru' => 'Что мы предлагаем',
            default => 'What We Offer',
        };
    }

    private function defaultAboutValuesTitle(string $locale): string
    {
        return match ($locale) {
            'de' => 'End-to-End-Produktionsexzellenz',
            'es' => 'Excelencia de producción integral',
            'fr' => 'Excellence de production de bout en bout',
            'it' => 'Eccellenza produttiva end-to-end',
            'pt' => 'Excelência de produção ponta a ponta',
            'tr' => 'Uçtan uca prodüksiyon mükemmelliği',
            'ru' => 'Комплексное производственное превосходство',
            default => 'End-to-End Production Excellence',
        };
    }

    private function defaultAboutTeamBadge(string $locale): string
    {
        return match ($locale) {
            'de' => 'Führung',
            'es' => 'Liderazgo',
            'fr' => 'Direction',
            'it' => 'Leadership',
            'pt' => 'Liderança',
            'tr' => 'Liderlik',
            'ru' => 'Руководство',
            default => 'Leadership',
        };
    }

    private function defaultAboutTeamTitle(string $locale): string
    {
        return match ($locale) {
            'de' => 'The Untold Story anführen',
            'es' => 'Liderando The Untold Story',
            'fr' => 'Diriger The Untold Story',
            'it' => 'Guidare The Untold Story',
            'pt' => 'Liderando The Untold Story',
            'tr' => 'The Untold Story\'ye liderlik',
            'ru' => 'Руководство The Untold Story',
            default => 'Leading The Untold Story',
        };
    }

    private function defaultAboutPartnersBadge(string $locale): string
    {
        return match ($locale) {
            'de' => 'Vertraut von Branchenführern',
            'es' => 'Con la confianza de líderes del sector',
            'fr' => 'Approuvé par les leaders du secteur',
            'it' => 'Scelti dai leader del settore',
            'pt' => 'Confiado por líderes do setor',
            'tr' => 'Sektör liderlerinin güvendiği',
            'ru' => 'Нам доверяют лидеры отрасли',
            default => 'Trusted By Industry Titans',
        };
    }

    private function defaultAboutPartnersTitle(string $locale): string
    {
        return $this->defaultAboutPartnersBadge($locale);
    }

    private function defaultServicesQuoteSubtext(string $locale): string
    {
        return match ($locale) {
            'de' => 'Senden Sie Ihr Briefing und wir antworten mit dem passenden Plan und den nächsten Schritten.',
            'es' => 'Envíe su briefing y responderemos con el plan adecuado y los próximos pasos.',
            'fr' => 'Envoyez votre brief et nous répondrons avec le bon plan et les prochaines étapes.',
            'it' => 'Invia il tuo brief e risponderemo con il piano giusto e i prossimi passi.',
            'pt' => 'Envie seu briefing e responderemos com o plano certo e os próximos passos.',
            'tr' => 'Brifinginizi gönderin, doğru plan ve sonraki adımlarla yanıt verelim.',
            'ru' => 'Отправьте бриф, и мы ответим с подходящим планом и следующими шагами.',
            default => 'Send your brief and we will respond with the right plan and next steps.',
        };
    }

    private function defaultContactInfoTitle(string $locale): string
    {
        return match ($locale) {
            'de' => 'Kontaktinformationen',
            'es' => 'Información de contacto',
            'fr' => 'Informations de contact',
            'it' => 'Informazioni di contatto',
            'pt' => 'Informações de contacto',
            'tr' => 'İletişim bilgileri',
            'ru' => 'Контактная информация',
            default => 'Contact Info',
        };
    }

    private function defaultContactOfficesTitle(string $locale): string
    {
        return match ($locale) {
            'de' => 'Unsere Büros',
            'es' => 'Nuestras oficinas',
            'fr' => 'Nos bureaux',
            'it' => 'I nostri uffici',
            'pt' => 'Os nossos escritórios',
            'tr' => 'Ofislerimiz',
            'ru' => 'Наши офисы',
            default => 'Our Offices',
        };
    }

    private function defaultContactOfficesSubtext(string $locale): string
    {
        return match ($locale) {
            'de' => 'Wir sind in Ägypten und den VAE präsent, um immer in Ihrer Nähe zu sein',
            'es' => 'Estamos presentes en Egipto y los EAU para estar siempre cerca de usted',
            'fr' => 'Nous sommes présents en Égypte et aux EAU pour rester toujours proches de vous',
            'it' => 'Siamo presenti in Egitto e negli EAU per essere sempre vicini a te',
            'pt' => 'Estamos presentes no Egito e nos EAU para estar sempre perto de si',
            'tr' => 'Mısır ve BAE\'de her zaman yanınızda olmak için buradayız',
            'ru' => 'Мы представлены в Египте и ОАЭ, чтобы всегда быть рядом с вами',
            default => 'We are present in Egypt and the UAE to always be close to you',
        };
    }

    private function defaultContactFormTitle(string $locale): string
    {
        return match ($locale) {
            'de' => 'Nachricht senden',
            'es' => 'Enviar un mensaje',
            'fr' => 'Envoyer un message',
            'it' => 'Invia un messaggio',
            'pt' => 'Enviar uma mensagem',
            'tr' => 'Mesaj gönder',
            'ru' => 'Отправить сообщение',
            default => 'Send a Message',
        };
    }

    private function defaultContactQuoteEmailLabel(string $locale): string
    {
        return match ($locale) {
            'de' => 'E-Mail-Adresse',
            'es' => 'Correo electrónico',
            'fr' => 'Adresse e-mail',
            'it' => 'Indirizzo email',
            'pt' => 'Endereço de e-mail',
            'tr' => 'E-posta adresi',
            'ru' => 'Адрес электронной почты',
            default => 'Email',
        };
    }

    private function defaultContactQuotePhoneLabel(string $locale): string
    {
        return match ($locale) {
            'de' => 'Telefon und WhatsApp',
            'es' => 'Teléfono y WhatsApp',
            'fr' => 'Téléphone et WhatsApp',
            'it' => 'Telefono e WhatsApp',
            'pt' => 'Telefone e WhatsApp',
            'tr' => 'Telefon ve WhatsApp',
            'ru' => 'Телефон и WhatsApp',
            default => 'Phone and WhatsApp',
        };
    }

    private function defaultFaqBadge(string $locale): string
    {
        return match ($locale) {
            'de' => 'FAQ',
            'es' => 'Preguntas frecuentes',
            'fr' => 'FAQ',
            'it' => 'FAQ',
            'pt' => 'FAQ',
            'tr' => 'SSS',
            'ru' => 'FAQ',
            default => 'FAQ',
        };
    }

    private function defaultFaqTitle(string $locale): string
    {
        return match ($locale) {
            'de' => 'Antworten auf Ihre wichtigsten Fragen',
            'es' => 'Respuestas a sus preguntas clave',
            'fr' => 'Réponses à vos questions clés',
            'it' => 'Risposte alle tue domande principali',
            'pt' => 'Respostas às suas principais perguntas',
            'tr' => 'En önemli sorularınıza yanıtlar',
            'ru' => 'Ответы на ваши ключевые вопросы',
            default => 'Answers to Your Key Questions',
        };
    }

    private function defaultContactLabel(string $locale, string $field): string
    {
        $labels = [
            'name' => [
                'de' => 'Vollständiger Name *',
                'es' => 'Nombre completo *',
                'fr' => 'Nom complet *',
                'it' => 'Nome completo *',
                'pt' => 'Nome completo *',
                'tr' => 'Ad soyad *',
                'ru' => 'Полное имя *',
                'default' => 'Full Name *',
            ],
            'email' => [
                'de' => 'E-Mail-Adresse *',
                'es' => 'Correo electrónico *',
                'fr' => 'Adresse e-mail *',
                'it' => 'Indirizzo email *',
                'pt' => 'Endereço de e-mail *',
                'tr' => 'E-posta adresi *',
                'ru' => 'Адрес электронной почты *',
                'default' => 'Email Address *',
            ],
            'phone' => [
                'de' => 'Telefonnummer',
                'es' => 'Número de teléfono',
                'fr' => 'Numéro de téléphone',
                'it' => 'Numero di telefono',
                'pt' => 'Número de telefone',
                'tr' => 'Telefon numarası',
                'ru' => 'Номер телефона',
                'default' => 'Phone Number',
            ],
            'service' => [
                'de' => 'Gewünschte Leistung',
                'es' => 'Servicio solicitado',
                'fr' => 'Service demandé',
                'it' => 'Servizio richiesto',
                'pt' => 'Serviço solicitado',
                'tr' => 'Talep edilen hizmet',
                'ru' => 'Запрашиваемая услуга',
                'default' => 'Requested Service',
            ],
            'message' => [
                'de' => 'Ihre Nachricht *',
                'es' => 'Su mensaje *',
                'fr' => 'Votre message *',
                'it' => 'Il tuo messaggio *',
                'pt' => 'A sua mensagem *',
                'tr' => 'Mesajınız *',
                'ru' => 'Ваше сообщение *',
                'default' => 'Your Message *',
            ],
            'choose_service' => [
                'de' => 'Leistung auswählen',
                'es' => 'Elegir un servicio',
                'fr' => 'Choisir un service',
                'it' => 'Scegli un servizio',
                'pt' => 'Escolher um serviço',
                'tr' => 'Hizmet seçin',
                'ru' => 'Выберите услугу',
                'default' => 'Choose a Service',
            ],
        ];

        return $labels[$field][$locale] ?? $labels[$field]['default'];
    }
}
