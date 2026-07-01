<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use App\Models\HeroSlide;
use App\Models\PageSection;
use App\Models\PortfolioItem;
use App\Models\Resource;
use App\Models\SeoMetaTranslation;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Support\MediaUrl;
use Illuminate\Console\Command;

class NormalizeMediaPathsCommand extends Command
{
    protected $signature = 'media:normalize-paths';

    protected $description = 'Convert stored full media URLs to storage-relative paths';

    public function handle(): int
    {
        $updated = 0;

        $updated += $this->normalizeModel(HeroSlide::class, ['image_url']);
        $updated += $this->normalizeModel(PortfolioItem::class, ['image_url']);
        $updated += $this->normalizeModel(Testimonial::class, ['avatar_url']);
        $updated += $this->normalizeModel(BlogPost::class, ['author_image_url', 'featured_image_url']);
        $updated += $this->normalizeModel(TeamMember::class, ['image_url']);
        $updated += $this->normalizeModel(Service::class, ['image_url']);
        $updated += $this->normalizeModel(Resource::class, ['file_url']);
        $updated += $this->normalizeModel(SeoMetaTranslation::class, ['og_image_url', 'twitter_image_url']);
        $updated += $this->normalizePageSections();

        $this->info("Normalized {$updated} record(s).");

        return self::SUCCESS;
    }

  /**
     * @param  class-string  $modelClass
     * @param  list<string>  $attributes
     */
    private function normalizeModel(string $modelClass, array $attributes): int
    {
        $updated = 0;

        $modelClass::query()->each(function ($model) use ($attributes, &$updated) {
            $changes = [];

            foreach ($attributes as $attribute) {
                $raw = $model->getAttributes()[$attribute] ?? null;

                if ($raw === null || $raw === '') {
                    continue;
                }

                $normalized = MediaUrl::toStoragePath($raw);

                if ($normalized !== $raw) {
                    $changes[$attribute] = $normalized;
                }
            }

            if ($changes !== []) {
                $model->updateQuietly($changes);
                $updated++;
            }
        });

        return $updated;
    }

    private function normalizePageSections(): int
    {
        $updated = 0;

        PageSection::query()->each(function (PageSection $section) use (&$updated) {
            $settings = $section->settings;

            if (! is_array($settings) || ! isset($settings['image']) || $settings['image'] === '') {
                return;
            }

            $normalized = MediaUrl::toStoragePath($settings['image']);

            if ($normalized === $settings['image']) {
                return;
            }

            $settings['image'] = $normalized;
            $section->updateQuietly(['settings' => $settings]);
            $updated++;
        });

        return $updated;
    }
}
