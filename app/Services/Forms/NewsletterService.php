<?php

namespace App\Services\Forms;

use App\Models\NewsletterSubscription;
use Illuminate\Support\Str;

class NewsletterService
{
    public function subscribe(string $email, string $locale): NewsletterSubscription
    {
        $existing = NewsletterSubscription::query()->where('email', $email)->first();

        if ($existing) {
            $existing->update([
                'is_active' => true,
                'locale' => $locale,
                'token' => $existing->token ?: Str::random(64),
                'subscribed_at' => now(),
                'unsubscribed_at' => null,
            ]);

            return $existing->fresh();
        }

        return NewsletterSubscription::query()->create([
            'email' => $email,
            'locale' => $locale,
            'is_active' => true,
            'token' => Str::random(64),
            'subscribed_at' => now(),
        ]);
    }

    public function unsubscribe(string $token): bool
    {
        $subscription = NewsletterSubscription::query()
            ->where('token', $token)
            ->where('is_active', true)
            ->first();

        if (! $subscription) {
            return false;
        }

        $subscription->update([
            'is_active' => false,
            'unsubscribed_at' => now(),
        ]);

        return true;
    }
}
