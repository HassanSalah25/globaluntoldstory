<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Models\Faq;
use Illuminate\Http\JsonResponse;

class FaqController extends ApiController
{
    public function index(): JsonResponse
    {
        $locale = app()->getLocale();

        $items = Faq::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with('translations')
            ->get()
            ->map(function ($faq) use ($locale) {
                $t = $faq->translate($locale);

                return [
                    'question' => $t?->question,
                    'answer' => $t?->answer,
                ];
            })->values()->all();

        return $this->success($items, $locale);
    }
}
