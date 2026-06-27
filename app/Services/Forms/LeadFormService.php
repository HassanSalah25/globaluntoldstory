<?php

namespace App\Services\Forms;

use App\Mail\NewLeadMail;
use App\Models\Lead;
use Illuminate\Support\Facades\Mail;

class LeadFormService
{
    public function store(array $data): Lead
    {
        $referenceId = $this->generateReferenceId();

        $lead = Lead::query()->create([
            'reference_id' => $referenceId,
            'type' => $data['type'] ?? 'quote',
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'service_id' => $data['service_id'] ?? null,
            'service_text' => $data['service'] ?? $data['service_text'] ?? null,
            'message' => $data['message'] ?? null,
            'budget' => $data['budget'] ?? null,
            'source' => $data['source'] ?? 'website',
            'locale' => $data['locale'] ?? app()->getLocale(),
            'status' => 'new',
        ]);

        $adminEmail = config('mail.admin_email', env('ADMIN_EMAIL'));

        if ($adminEmail) {
            Mail::to($adminEmail)->queue(new NewLeadMail($lead));
        }

        return $lead;
    }

    private function generateReferenceId(): string
    {
        $year = now()->year;
        $sequence = Lead::query()
            ->whereYear('created_at', $year)
            ->count() + 1;

        return sprintf('LD-%d-%05d', $year, $sequence);
    }
}
