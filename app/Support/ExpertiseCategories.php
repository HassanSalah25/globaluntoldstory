<?php

namespace App\Support;

class ExpertiseCategories
{
    /** @return list<array{en: string, ar: string, sort_order: int}> */
    public static function all(): array
    {
        return [
            ['en' => 'Commercials', 'ar' => 'إعلانات', 'sort_order' => 0],
            ['en' => 'Documentaries', 'ar' => 'وثائقيات', 'sort_order' => 1],
            ['en' => 'Branded Films', 'ar' => 'أفلام علامات', 'sort_order' => 2],
            ['en' => 'Live Events', 'ar' => 'فعاليات حية', 'sort_order' => 3],
            ['en' => 'Podcasts', 'ar' => 'بودكاست', 'sort_order' => 4],
            ['en' => 'Motion/CGI', 'ar' => 'موشن/CGI', 'sort_order' => 5],
        ];
    }

    public static function findByEn(string $tag): ?array
    {
        foreach (self::all() as $category) {
            if (strcasecmp($category['en'], $tag) === 0) {
                return $category;
            }
        }

        return null;
    }
}
