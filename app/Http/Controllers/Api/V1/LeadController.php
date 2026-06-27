<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\StoreQuoteRequest;
use App\Services\Forms\LeadFormService;
use Illuminate\Http\JsonResponse;

class LeadController extends ApiController
{
    public function __construct(
        private readonly LeadFormService $leadForm,
    ) {}

    public function storeQuote(StoreQuoteRequest $request): JsonResponse
    {
        $lead = $this->leadForm->store([
            ...$request->validated(),
            'type' => 'quote',
        ]);

        return $this->success([
            'reference_id' => $lead->reference_id,
            'message' => 'Quote request submitted successfully.',
        ], app()->getLocale(), 201);
    }
}
