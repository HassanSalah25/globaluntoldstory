<?php

namespace App\Services\Forms;

use App\Mail\NewContactRequestMail;
use App\Models\ContactRequest;
use Illuminate\Support\Facades\Mail;

class ContactFormService
{
    public function store(array $data): ContactRequest
    {
        $referenceId = $this->generateReferenceId();

        $contact = ContactRequest::query()->create([
            'reference_id' => $referenceId,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'service' => $data['service'] ?? null,
            'budget' => $data['budget'] ?? null,
            'message' => $data['message'],
            'locale' => $data['locale'] ?? app()->getLocale(),
            'ip' => $data['ip'] ?? null,
            'user_agent' => $data['user_agent'] ?? null,
            'status' => 'new',
        ]);

        $adminEmail = config('mail.admin_email');

        if ($adminEmail) {
            Mail::to($adminEmail)->send(new NewContactRequestMail($contact));
        }

        return $contact;
    }

    private function generateReferenceId(): string
    {
        $year = now()->year;
        $sequence = ContactRequest::query()
            ->whereYear('created_at', $year)
            ->count() + 1;

        return sprintf('CR-%d-%05d', $year, $sequence);
    }
}
