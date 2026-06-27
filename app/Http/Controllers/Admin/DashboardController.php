<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\ContactRequest;
use App\Models\Lead;
use App\Models\NewsletterSubscription;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'contact_requests' => [
                    'total' => ContactRequest::query()->count(),
                    'new' => ContactRequest::query()->where('status', 'new')->count(),
                    'unread' => ContactRequest::query()->whereNull('read_at')->count(),
                ],
                'leads' => [
                    'total' => Lead::query()->count(),
                    'new' => Lead::query()->where('status', 'new')->count(),
                ],
                'newsletter' => [
                    'total' => NewsletterSubscription::query()->count(),
                    'active' => NewsletterSubscription::query()->where('is_active', true)->count(),
                ],
                'blog_posts' => BlogPost::query()->where('is_published', true)->count(),
            ],
        ]);
    }
}
