<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\ContactRequest;
use App\Models\Lead;
use App\Models\NewsletterSubscription;
use App\Models\PortfolioItem;
use App\Models\Service;
use App\Models\TeamMember;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'services'         => Service::count(),
            'portfolio_items'  => PortfolioItem::count(),
            'blog_posts'       => BlogPost::count(),
            'team_members'     => TeamMember::count(),
            'new_leads'        => Lead::where('status', 'new')->count(),
            'unread_contacts'  => ContactRequest::whereNull('read_at')->count(),
            'newsletter_subs'  => NewsletterSubscription::count(),
        ];

        $recentContacts = ContactRequest::latest()
            ->limit(5)
            ->get();

        $recentLeads = Lead::with('service')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard.index', compact('stats', 'recentContacts', 'recentLeads'));
    }
}
