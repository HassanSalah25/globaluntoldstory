<?php

namespace App\Services\Content;

use App\Models\BlogPost;
use App\Services\Seo\SeoService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BlogService
{
    public function __construct(
        private readonly SeoService $seo,
    ) {}

    public function list(
        string $locale,
        ?string $category = null,
        ?string $tag = null,
        ?string $search = null,
        int $limit = 0,
        int $perPage = 12,
        int $page = 1,
    ): array {
        $query = BlogPost::query()
            ->where('is_published', true)
            ->orderByDesc('published_at')
            ->orderBy('sort_order')
            ->with(['translations', 'category.translations']);

        if ($category) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $category));
        }

        if ($tag) {
            $query->whereHas('translations', function ($q) use ($tag, $locale) {
                $q->where('locale', $locale)->whereJsonContains('tags', $tag);
            });
        }

        if ($search) {
            $query->whereHas('translations', function ($q) use ($search, $locale) {
                $q->where('locale', $locale)
                    ->where(function ($inner) use ($search) {
                        $inner->where('title', 'like', "%{$search}%")
                            ->orWhere('excerpt', 'like', "%{$search}%")
                            ->orWhere('body', 'like', "%{$search}%");
                    });
            });
        }

        if ($limit > 0) {
            $items = $query->limit($limit)->get();

            return [
                'items' => $items->map(fn ($post) => $this->mapPost($post, $locale))->values()->all(),
                'pagination' => null,
            ];
        }

        /** @var LengthAwarePaginator $paginator */
        $paginator = $query->paginate($perPage, ['*'], 'page', $page);

        return [
            'items' => collect($paginator->items())->map(fn ($post) => $this->mapPost($post, $locale))->values()->all(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ];
    }

    public function getBySlug(string $slug, string $locale): array
    {
        $post = BlogPost::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->with(['translations', 'category.translations'])
            ->firstOrFail();

        $mapped = $this->mapPost($post, $locale, detailed: true);
        $mapped['seo'] = $this->seo->getForBlogPost($post, $locale);

        return $mapped;
    }

    private function mapPost(BlogPost $post, string $locale, bool $detailed = false): array
    {
        $t = $post->translate($locale);
        $categoryT = $post->category?->translate($locale);

        $data = [
            'id' => $post->slug,
            'slug' => $post->slug,
            'title' => $t?->title,
            'excerpt' => $t?->excerpt,
            'date' => $post->published_at?->translatedFormat($locale === 'ar' ? 'j F Y' : 'F j, Y'),
            'publishedAt' => $post->published_at?->toIso8601String(),
            'category' => $categoryT?->name,
            'categorySlug' => $post->category?->slug,
            'authorName' => $post->author_name,
            'authorImage' => $post->author_image_url,
            'featuredImage' => $post->featured_image_url,
            'readTimeMinutes' => $post->read_time_minutes,
            'tags' => $t?->tags ?? [],
            'isFeatured' => $post->is_featured,
        ];

        if ($detailed) {
            $data['body'] = $t?->body;
        }

        return $data;
    }
}
