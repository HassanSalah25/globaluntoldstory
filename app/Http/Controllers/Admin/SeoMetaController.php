<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoMeta;
use App\Support\AdminLocales;
use Illuminate\Http\Request;

class SeoMetaController extends Controller
{
    public function index()
    {
        $seoMetas = SeoMeta::with('translations')->paginate(20);

        return view('admin.seo.index', compact('seoMetas'));
    }

    public function edit(SeoMeta $seoMeta)
    {
        $seoMeta->load('translations');
        $translations = $seoMeta->translations->keyBy('locale');

        return view('admin.seo.edit', compact('seoMeta', 'translations'));
    }

    public function update(Request $request, SeoMeta $seoMeta)
    {
        $request->validate([
            'canonical_url'   => 'nullable|url',
            'robots'          => 'nullable|string|in:index-follow,noindex-nofollow,noindex-follow,index-nofollow',
            'structured_data' => 'nullable|string',
        ]);

        $seoMeta->update([
            'canonical_url'   => $request->input('canonical_url'),
            'robots'          => $request->input('robots'),
            'structured_data' => $request->input('structured_data') ? json_decode($request->input('structured_data'), true) : null,
        ]);

        AdminLocales::syncTranslations($seoMeta, $request, [
            'meta_title',
            'meta_description',
            'og_title',
            'og_description',
            'og_image_url',
            'twitter_title',
            'twitter_description',
            'twitter_image_url',
        ]);

        return redirect()->back()->with('success', 'SEO settings updated successfully.');
    }

    public function createForPage(Request $request)
    {
        $request->validate([
            'page_slug' => 'required|string|max:255|unique:seo_metas,page_slug',
        ]);

        $seoMeta = SeoMeta::create([
            'page_slug' => $request->input('page_slug'),
        ]);

        return redirect()->route('admin.seo.edit', $seoMeta)->with('success', 'SEO entry created. Fill in the details below.');
    }
}
