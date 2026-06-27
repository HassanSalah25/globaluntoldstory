<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\StoreNewsletterRequest;
use App\Services\Forms\NewsletterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsletterController extends ApiController
{
    public function __construct(
        private readonly NewsletterService $newsletter,
    ) {}

    public function subscribe(StoreNewsletterRequest $request): JsonResponse
    {
        $subscription = $this->newsletter->subscribe(
            $request->validated('email'),
            $request->validated('locale') ?? app()->getLocale(),
        );

        return $this->success([
            'email' => $subscription->email,
            'message' => 'Subscribed successfully.',
        ], app()->getLocale(), 201);
    }

    public function unsubscribe(Request $request): JsonResponse
    {
        $request->validate([
            'token' => ['required', 'string'],
        ]);

        $success = $this->newsletter->unsubscribe($request->input('token'));

        if (! $success) {
            return $this->error('Invalid or expired unsubscribe token.', 404);
        }

        return $this->success([
            'message' => 'Unsubscribed successfully.',
        ], app()->getLocale());
    }
}
