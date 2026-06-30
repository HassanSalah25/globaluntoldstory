<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Models\Page;
use App\Models\PartnerLabel;
use App\Models\SkillBar;
use App\Models\Stat;
use App\Models\TeamMember;
use App\Models\TimelineEvent;
use App\Models\ValueItem;
use Illuminate\Http\JsonResponse;

class AboutController extends ApiController
{
    public function index(): JsonResponse
    {
        $locale = app()->getLocale();

        $page = Page::query()->where('slug', 'about')->with('translations')->first();
        $pageT = $page?->translate($locale);

        $teamMembers = TeamMember::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with('translations')
            ->get()
            ->map(fn ($m) => [
                'name' => $m->translate($locale)?->name,
                'role' => $m->translate($locale)?->role,
                'bio' => $m->translate($locale)?->bio,
                'image' => $m->image_url,
                'slug' => $m->slug,
            ]);

        $timeline = TimelineEvent::query()
            ->orderBy('sort_order')
            ->with('translations')
            ->get()
            ->map(fn ($e) => [
                'year' => $e->year,
                'icon' => $e->icon,
                'title' => $e->translate($locale)?->title,
                'description' => $e->translate($locale)?->description,
            ]);

        $skills = SkillBar::query()
            ->orderBy('sort_order')
            ->with('translations')
            ->get()
            ->map(fn ($s) => [
                'label' => $s->translate($locale)?->label,
                'percent' => $s->percent,
                'color' => $s->color,
            ]);

        $values = ValueItem::query()
            ->orderBy('sort_order')
            ->with('translations')
            ->get()
            ->map(fn ($v) => [
                'icon' => $v->icon,
                'title' => $v->translate($locale)?->title,
                'description' => $v->translate($locale)?->description,
            ]);

        $stats = Stat::query()
            ->where('context', 'about')
            ->orderBy('sort_order')
            ->with('translations')
            ->get()
            ->map(fn ($s) => [
                'value' => $s->numeric_value.($s->suffix ?? ''),
                'label' => $s->translate($locale)?->label,
                'icon' => $s->icon,
            ]);

        $partnerLabels = PartnerLabel::query()
            ->orderBy('sort_order')
            ->with('translations')
            ->get()
            ->map(fn ($p) => $p->translate($locale)?->label)
            ->filter()
            ->values();

        return $this->success([
            'page' => [
                'title' => $pageT?->title,
                'subtitle' => $pageT?->subtitle,
                'badge' => $pageT?->badge,
            ],
            'team' => $teamMembers->values()->all(),
            'timeline' => $timeline->values()->all(),
            'skills' => $skills->values()->all(),
            'values' => $values->values()->all(),
            'stats' => $stats->values()->all(),
            'partnerLabels' => $partnerLabels->all(),
        ], $locale);
    }
}
