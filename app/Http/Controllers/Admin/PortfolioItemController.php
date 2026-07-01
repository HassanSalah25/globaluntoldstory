<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\PortfolioItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PortfolioItemController extends Controller
{
    public function index(Request $request)
    {
        $query = PortfolioItem::with(['translations', 'category.translations'])->orderBy('sort_order');

        if ($search = $request->input('search')) {
            $query->whereHas('translations', function ($q) use ($search) {
                $q->where('locale', 'en')->where('title', 'like', "%{$search}%");
            });
        }

        $portfolios = $query->paginate(15)->withQueryString();

        return view('admin.portfolio.index', compact('portfolios'));
    }

    public function create()
    {
        $locales = ['en', 'ar'];
        $categories = Category::with('translations')->where('type', 'portfolio')->get();

        return view('admin.portfolio.create', compact('locales', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id'   => 'nullable|exists:categories,id',
            'client_name'   => 'nullable|string|max:255',
            'image_url'     => 'nullable|string|max:255',
            'duration'      => 'nullable|string|max:255',
            'budget'        => 'nullable|string|max:255',
            'results'       => 'nullable|string|max:255',
            'metric'        => 'nullable|string|max:255',
            'sort_order'    => 'required|integer',
            'grid_size'     => 'nullable|in:small,medium,large',
            'title_en'      => 'required|string|max:255',
            'title_ar'      => 'required|string|max:255',
            'results_text_en' => 'nullable|string',
            'results_text_ar' => 'nullable|string',
            'metric_en'     => 'nullable|string|max:255',
            'metric_ar'     => 'nullable|string|max:255',
        ]);

        $portfolioItem = PortfolioItem::create([
            'slug'        => Str::slug($request->title_en),
            'category_id' => $request->category_id,
            'client_name' => $request->client_name,
            'image_url'   => $request->image_url,
            'duration'    => $request->duration,
            'budget'      => $request->budget,
            'results'     => $request->results,
            'metric'      => $request->metric,
            'sort_order'  => $request->sort_order,
            'is_featured' => $request->boolean('is_featured'),
            'is_active'   => $request->boolean('is_active'),
            'grid_size'   => $request->grid_size ?? 'medium',
        ]);

        foreach (['en', 'ar'] as $locale) {
            $portfolioItem->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title'        => $request->input("title_{$locale}"),
                    'results_text' => $request->input("results_text_{$locale}"),
                    'metric'       => $request->input("metric_{$locale}"),
                ]
            );
        }

        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio item created successfully.');
    }

    public function edit(PortfolioItem $portfolio)
    {
        $locales = ['en', 'ar'];
        $translations = $portfolio->translations->keyBy('locale');
        $categories = Category::with('translations')->where('type', 'portfolio')->get();

        return view('admin.portfolio.edit', compact('portfolio', 'locales', 'translations', 'categories'));
    }

    public function update(Request $request, PortfolioItem $portfolio)
    {
        $request->validate([
            'slug'          => 'required|string|max:255|unique:portfolio_items,slug,' . $portfolio->id,
            'category_id'   => 'nullable|exists:categories,id',
            'client_name'   => 'nullable|string|max:255',
            'image_url'     => 'nullable|string|max:255',
            'duration'      => 'nullable|string|max:255',
            'budget'        => 'nullable|string|max:255',
            'results'       => 'nullable|string|max:255',
            'metric'        => 'nullable|string|max:255',
            'sort_order'    => 'required|integer',
            'grid_size'     => 'nullable|in:small,medium,large',
            'title_en'      => 'required|string|max:255',
            'title_ar'      => 'required|string|max:255',
            'results_text_en' => 'nullable|string',
            'results_text_ar' => 'nullable|string',
            'metric_en'     => 'nullable|string|max:255',
            'metric_ar'     => 'nullable|string|max:255',
        ]);

        $portfolio->update([
            'slug'        => $request->slug,
            'category_id' => $request->category_id,
            'client_name' => $request->client_name,
            'image_url'   => $request->image_url,
            'duration'    => $request->duration,
            'budget'      => $request->budget,
            'results'     => $request->results,
            'metric'      => $request->metric,
            'sort_order'  => $request->sort_order,
            'is_featured' => $request->boolean('is_featured'),
            'is_active'   => $request->boolean('is_active'),
            'grid_size'   => $request->grid_size ?? 'medium',
        ]);

        foreach (['en', 'ar'] as $locale) {
            $portfolio->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title'        => $request->input("title_{$locale}"),
                    'results_text' => $request->input("results_text_{$locale}"),
                    'metric'       => $request->input("metric_{$locale}"),
                ]
            );
        }

        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio item updated successfully.');
    }

    public function destroy(PortfolioItem $portfolio)
    {
        $portfolio->delete();

        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio item deleted successfully.');
    }

    public function toggle(PortfolioItem $portfolioItem)
    {
        $portfolioItem->update(['is_active' => ! $portfolioItem->is_active]);

        return redirect()->back()->with('success', 'Portfolio item status updated.');
    }
}
