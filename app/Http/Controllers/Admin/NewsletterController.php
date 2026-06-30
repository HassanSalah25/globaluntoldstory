<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscription;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsletterSubscription::latest('subscribed_at');

        if ($request->input('filter') === 'active') {
            $query->where('is_active', true);
        } elseif ($request->input('filter') === 'inactive') {
            $query->where('is_active', false);
        }

        $subscriptions = $query->paginate(25)->withQueryString();
        $totalCount    = NewsletterSubscription::count();
        $activeCount   = NewsletterSubscription::where('is_active', true)->count();

        return view('admin.newsletter.index', compact('subscriptions', 'totalCount', 'activeCount'));
    }

    public function toggle(NewsletterSubscription $newsletterSubscription)
    {
        $newsletterSubscription->update([
            'is_active'       => ! $newsletterSubscription->is_active,
            'unsubscribed_at' => $newsletterSubscription->is_active ? now() : null,
        ]);

        return redirect()->back()->with('success', 'Subscription status updated.');
    }

    public function export()
    {
        $subscriptions = NewsletterSubscription::where('is_active', true)
            ->orderBy('subscribed_at')
            ->get(['email', 'locale', 'subscribed_at']);

        return response()->streamDownload(function () use ($subscriptions) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['email', 'locale', 'subscribed_at']);
            foreach ($subscriptions as $sub) {
                fputcsv($handle, [
                    $sub->email,
                    $sub->locale,
                    $sub->subscribed_at ? $sub->subscribed_at->toDateTimeString() : '',
                ]);
            }
            fclose($handle);
        }, 'subscribers.csv', [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=subscribers.csv',
        ]);
    }
}
