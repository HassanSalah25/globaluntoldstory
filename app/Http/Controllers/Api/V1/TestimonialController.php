<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;

class TestimonialController extends ApiController
{
    public function index(): JsonResponse
    {
        $locale = app()->getLocale();

        $items = Testimonial::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with('translations')
            ->get()
            ->map(fn ($t) => [
                'name' => $t->translate($locale)?->name,
                'role' => $t->translate($locale)?->role,
                'text' => $t->translate($locale)?->text,
                'rating' => $t->rating,
                'avatar' => $t->avatar_url,
                'type' => $t->type,
            ]);

        return $this->success($items->values()->all(), $locale);
    }
}
