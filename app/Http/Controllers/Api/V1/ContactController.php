<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\StoreContactRequest;
use App\Services\Forms\ContactFormService;
use Illuminate\Http\JsonResponse;

class ContactController extends ApiController
{
    public function __construct(
        private readonly ContactFormService $contactForm,
    ) {}

    public function store(StoreContactRequest $request): JsonResponse
    {
        $contact = $this->contactForm->store([
            ...$request->validated(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return $this->success([
            'reference_id' => $contact->reference_id,
            'message' => 'Contact request submitted successfully.',
        ], app()->getLocale(), 201);
    }
}
