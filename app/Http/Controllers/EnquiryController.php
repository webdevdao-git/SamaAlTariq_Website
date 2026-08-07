<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnquiryRequest;
use App\Mail\EnquiryReceived;
use App\Models\Enquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EnquiryController extends Controller
{
    /**
     * Stores a landing-page enquiry, then notifies the business inbox.
     *
     * The row is committed before the email is attempted. If SMTP is broken the
     * visitor still gets a success message, because the lead is already saved —
     * a misconfigured mailbox must never ask someone to fill the form in again.
     */
    public function store(StoreEnquiryRequest $request): RedirectResponse
    {
        $enquiry = Enquiry::create([
            ...$request->safe()->except('company'),
            'ip' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
        ]);

        try {
            Mail::to(config('site.enquiry_to') ?: config('mail.from.address'))
                ->send(new EnquiryReceived($enquiry));
        } catch (\Throwable $e) {
            Log::error('Enquiry saved but the notification email failed.', [
                'enquiry_id' => $enquiry->id,
                'exception' => $e->getMessage(),
            ]);
        }

        return back()
            ->with('enquiry_status', "Thank you — your enquiry has been sent. We'll be in touch shortly.")
            ->withFragment('contact');
    }
}
