<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Category;
use App\Support\AdminLocales;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::with(['translations', 'category.translations'])
            ->orderBy('published_at', 'desc');

        if ($search = $request->input('search')) {
            $query->whereHas('translations', function ($q) use ($search) {
                $q->where('locale', 'en')->where('title', 'like', "%{$search}%");
            });
        }

        $blogPosts = $query->paginate(15)->withQueryString();

        return view('admin.blog.index', ['posts' => $blogPosts]);
    }

    public function create()
    {
        $categories = Category::with('translations')->get();

        return view('admin.blog.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(array_merge([
            'category_id'        => 'nullable|exists:categories,id',
            'author_name'        => 'nullable|string|max:255',
            'author_image_url'   => 'nullable|string|max:255',
            'featured_image_url' => 'nullable|string|max:255',
            'published_at'       => 'nullable|date',
            'read_time_minutes'  => 'nullable|integer|min:1',
            'sort_order'         => 'required|integer',
        ], AdminLocales::fieldRules([
            'title'   => 'string|max:255',
            'excerpt' => 'nullable|string',
            'body'    => 'nullable|string',
            'tags'    => 'nullable|string',
        ], requiredFields: ['title'])));

        $blogPost = BlogPost::create([
            'slug'               => Str::slug($request->title_en),
            'category_id'        => $request->category_id,
            'author_name'        => $request->author_name,
            'author_image_url'   => $request->author_image_url,
            'featured_image_url' => $request->featured_image_url,
            'published_at'       => $request->published_at,
            'read_time_minutes'  => $request->read_time_minutes,
            'is_featured'        => $request->boolean('is_featured'),
            'is_published'       => $request->boolean('is_published'),
            'sort_order'         => $request->sort_order,
        ]);

        $this->syncBlogPostTranslations($blogPost, $request);

        return redirect()->route('admin.blog.index')->with('success', 'Blog post created successfully.');
    }

    public function edit(BlogPost $blogPost)
    {
        $translations = $blogPost->translations->keyBy('locale');
        $categories = Category::with('translations')->get();

        return view('admin.blog.edit', [
            'post'         => $blogPost,
            'translations' => $translations,
            'categories'   => $categories,
        ]);
    }

    public function update(Request $request, BlogPost $blogPost)
    {
        $request->validate(array_merge([
            'slug'               => 'required|string|max:255|unique:blog_posts,slug,' . $blogPost->id,
            'category_id'        => 'nullable|exists:categories,id',
            'author_name'        => 'nullable|string|max:255',
            'author_image_url'   => 'nullable|string|max:255',
            'featured_image_url' => 'nullable|string|max:255',
            'published_at'       => 'nullable|date',
            'read_time_minutes'  => 'nullable|integer|min:1',
            'sort_order'         => 'required|integer',
        ], AdminLocales::fieldRules([
            'title'   => 'string|max:255',
            'excerpt' => 'nullable|string',
            'body'    => 'nullable|string',
            'tags'    => 'nullable|string',
        ], requiredFields: ['title'])));

        $blogPost->update([
            'slug'               => $request->slug,
            'category_id'        => $request->category_id,
            'author_name'        => $request->author_name,
            'author_image_url'   => $request->author_image_url,
            'featured_image_url' => $request->featured_image_url,
            'published_at'       => $request->published_at,
            'read_time_minutes'  => $request->read_time_minutes,
            'is_featured'        => $request->boolean('is_featured'),
            'is_published'       => $request->boolean('is_published'),
            'sort_order'         => $request->sort_order,
        ]);

        $this->syncBlogPostTranslations($blogPost, $request);

        return redirect()->route('admin.blog.index')->with('success', 'Blog post updated successfully.');
    }

    public function destroy(BlogPost $blogPost)
    {
        $blogPost->delete();

        return redirect()->route('admin.blog.index')->with('success', 'Blog post deleted successfully.');
    }

    public function toggle(BlogPost $blogPost)
    {
        $blogPost->update(['is_published' => ! $blogPost->is_published]);

        return redirect()->back()->with('success', 'Blog post publish status updated.');
    }

    private function syncBlogPostTranslations(BlogPost $blogPost, Request $request): void
    {
        foreach (AdminLocales::codes() as $locale) {
            $rawTags = $request->input("tags_{$locale}");
            $tags = $rawTags
                ? array_values(array_filter(array_map('trim', explode(',', $rawTags))))
                : [];

            $blogPost->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title'   => $request->input("title_{$locale}"),
                    'excerpt' => $request->input("excerpt_{$locale}"),
                    'body'    => $request->input("body_{$locale}"),
                    'tags'    => $tags,
                ]
            );
        }
    }
}
