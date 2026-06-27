<?php

namespace App\Services\Content;

use App\Models\PortfolioItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PortfolioService
{
    public function list(string $locale, ?string $category = null, int $perPage = 12, int $page = 1): array
    {
        $query = PortfolioItem::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with(['translations', 'category.translations']);

        if ($category) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $category));
        }

        if ($perPage > 0) {
            /** @var LengthAwarePaginator $paginator */
            $paginator = $query->paginate($perPage, ['*'], 'page', $page);

            return [
                'items' => collect($paginator->items())->map(fn ($item) => $this->mapItem($item, $locale))->values()->all(),
                'pagination' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                ],
            ];
        }

        $items = $query->get();

        return [
            'items' => $items->map(fn ($item) => $this->mapItem($item, $locale))->values()->all(),
            'pagination' => null,
        ];
    }

    public function getBySlug(string $slug, string $locale): array
    {
        $item = PortfolioItem::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with(['translations', 'category.translations'])
            ->firstOrFail();

        return $this->mapItem($item, $locale, detailed: true);
    }

    private function mapItem(PortfolioItem $item, string $locale, bool $detailed = false): array
    {
        $t = $item->translate($locale);
        $categoryT = $item->category?->translate($locale);

        $data = [
            'slug' => $item->slug,
            'title' => $t?->title,
            'client' => $item->client_name,
            'image' => $item->image_url,
            'category' => $categoryT?->name,
            'categorySlug' => $item->category?->slug,
            'duration' => $item->duration,
            'budget' => $item->budget,
            'results' => $t?->results_text ?? $item->results,
            'metric' => $t?->metric ?? $item->metric,
            'gridSize' => $item->grid_size,
            'isFeatured' => $item->is_featured,
        ];

        if ($detailed) {
            $data['categoryIcon'] = $item->category?->icon;
        }

        return $data;
    }
}
