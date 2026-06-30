<?php

namespace App\Services\Content;

use App\Models\ClientLogo;
use App\Models\Menu;
use App\Models\Office;
use App\Models\PartnerLabel;
use App\Models\Service;
use App\Services\Settings\SettingService;

class LayoutService
{
    public function __construct(
        private readonly SettingService $settings,
    ) {}

    public function getLayout(string $locale): array
    {
        return [
            'site_config' => $this->settings->getSiteConfig($locale),
            'nav_links' => $this->getNavLinks($locale),
            'footer' => $this->getFooter($locale),
            'announcement' => [
                'text' => $this->settings->get('announcement.text', $locale),
                'enabled' => (bool) (int) $this->settings->get('announcement.enabled', $locale),
            ],
            'common_labels' => $this->settings->getCommonLabels($locale),
            'partner_labels' => $this->getPartnerLabels($locale),
            'client_logos' => $this->getClientLogos($locale),
        ];
    }

    private function getPartnerLabels(string $locale): array
    {
        return PartnerLabel::query()->orderBy('sort_order')->with('translations')->get()
            ->map(fn ($p) => $p->translate($locale)?->label)->filter()->values()->all();
    }

    private function getClientLogos(string $locale): array
    {
        return ClientLogo::query()->where('is_active', true)->orderBy('sort_order')->with('translations')->get()
            ->map(fn ($c) => ['name' => $c->name, 'displayName' => $c->translate($locale)?->display_name])->values()->all();
    }

    private function getNavLinks(string $locale): array
    {
        $menu = Menu::query()
            ->where('slug', 'main')
            ->with(['items' => fn ($q) => $q->where('is_active', true)->with('translations')])
            ->first();

        if (! $menu) {
            return [];
        }

        return $menu->items->map(function ($item) use ($locale) {
            $translation = $item->translate($locale);

            return [
                'href' => $item->url,
                'label' => $translation?->label ?? $item->url,
            ];
        })->values()->all();
    }

    private function getFooter(string $locale): array
    {
        $aboutMenu = Menu::query()
            ->where('slug', 'footer-about')
            ->with(['items' => fn ($q) => $q->where('is_active', true)->with('translations')])
            ->first();

        $aboutLinks = $aboutMenu?->items->map(function ($item) use ($locale) {
            $translation = $item->translate($locale);

            return [
                'href' => $item->url,
                'label' => $translation?->label ?? $item->url,
            ];
        })->values()->all() ?? [];

        $services = Service::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with('translations')
            ->limit(4)
            ->get();

        $serviceLinks = $services->map(function ($service) use ($locale) {
            $translation = $service->translate($locale);

            return [
                'href' => '/services#'.$service->slug,
                'label' => $translation?->title ?? $service->slug,
            ];
        })->values()->all();

        $offices = Office::query()
            ->orderBy('sort_order')
            ->with('translations')
            ->get()
            ->map(function ($office) use ($locale) {
                $translation = $office->translate($locale);

                return [
                    'region' => $translation?->title ?? $office->city,
                    'address' => $office->address,
                    'phone' => $office->phone,
                ];
            })->values()->all();

        return [
            'brandDesc' => $this->settings->get('footer.brand_desc', $locale),
            'aboutTitle' => $locale === 'ar' ? 'عن الشركة' : 'About',
            'aboutLinks' => $aboutLinks,
            'servicesTitle' => $locale === 'ar' ? 'صفحات أخرى' : 'More Pages',
            'serviceLinks' => $serviceLinks,
            'quickLinks' => $locale === 'ar' ? 'روابط سريعة' : 'Quick Links',
            'contactUs' => $this->settings->get('common.contact_us', $locale),
            'emailLabel' => $locale === 'ar' ? 'البريد الإلكتروني' : 'Email',
            'allRights' => $locale === 'ar' ? 'جميع الحقوق محفوظة.' : 'All rights reserved.',
            'adminDashboard' => $locale === 'ar' ? 'لوحة تحكم الإدارة ←' : 'Admin Dashboard →',
            'offices' => $offices,
        ];
    }
}
