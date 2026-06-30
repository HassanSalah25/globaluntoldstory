<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Category;
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

        return view('admin.blog-posts.index', compact('blogPosts'));
    }

    public function create()
    {
        $locales = ['en', 'ar'];
        $categories = Category::with('translations')->get();

        return view('admin.blog-posts.create', compact('locales', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id'         => 'nullable|exists:categories,id',
            'author_name'         => 'nullable|string|max:255',
            'author_image_url'    => 'nullable|string|max:255',
            'featured_image_url'  => 'nullable|string|max:255',
            'published_at'        => 'nullable|date',
            'read_time_minutes'   => 'nullable|integer|min:1',
            'sort_order'          => 'required|integer',
            'title_en'            => 'required|string|max:255',
            'title_ar'            => 'required|string|max:255',
            'excerpt_en'          => 'nullable|string',
            'excerpt_ar'          => 'nullable|string',
            'body_en'             => 'nullable|string',
            'body_ar'             => 'nullable|string',
            'tags_en'             => 'nullable|string',
            'tags_ar'             => 'nullable|string',
        ]);

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

        foreach (['en', 'ar'] as $locale) {
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

        return redirect()->route('admin.blog-posts.index')->with('success', 'Blog post created successfully.');
    }

    public function edit(BlogPost $blogPost)
    {
        $locales = ['en', 'ar'];
        $translations = $blogPost->translations->keyBy('locale');
        $categories = Category::with('translations')->get();

        return view('admin.blog-posts.edit', compact('blogPost', 'locales', 'translations', 'categories'));
    }

    public function update(Request $request, BlogPost $blogPost)
    {
        $request->validate([
            'slug'                => 'required|string|max:255|unique:blog_posts,slug,' . $blogPost->id,
            'category_id'         => 'nullable|exists:categories,id',
            'author_name'         => 'nullable|string|max:255',
            'author_image_url'    => 'nullable|string|max:255',
            'featured_image_url'  => 'nullable|string|max:255',
            'published_at'        => 'nullable|date',
            'read_time_minutes'   => 'nullable|integer|min:1',
            'sort_order'          => 'required|integer',
            'title_en'            => 'required|string|max:255',
            'title_ar'            => 'required|string|max:255',
            'excerpt_en'          => 'nullable|string',
            'excerpt_ar'          => 'nullable|string',
            'body_en'             => 'nullable|string',
            'body_ar'             => 'nullable|string',
            'tags_en'             => 'nullable|string',
            'tags_ar'             => 'nullable|string',
        ]);

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

        foreach (['en', 'ar'] as $locale) {
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

        return redirect()->route('admin.blog-posts.index')->with('success', 'Blog post updated successfully.');
    }

    public function destroy(BlogPost $blogPost)
    {
        $blogPost->delete();

        return redirect()->route('admin.blog-posts.index')->with('success', 'Blog post deleted successfully.');
    }

    public function toggle(BlogPost $blogPost)
    {
        $blogPost->update(['is_published' => ! $blogPost->is_published]);

        return redirect()->back()->with('success', 'Blog post publish status updated.');
    }
}
