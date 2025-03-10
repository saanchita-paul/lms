<?php

namespace App\Services;


use App\Models\Clinic;
use Illuminate\Support\Facades\Mail;

class DnsEmailVerificationService
{
    public function sendVerificationEmail($id)
    {
        $clinic = Clinic::findOrFail($id);

        if ($clinic->domain_verification !== Clinic::DNS_STATUS_VERIFY && $clinic->domain_verification !== Clinic::DNS_STATUS_FAILED) {
            return response()->json(['message' => 'Invalid status for sending verification email.'], 400);
        }

        $clinic_name = $clinic->clinic_name;
        $microsite_website = $clinic->microsite_website;

        Mail::send('emails.domain_verification', ['clinic_name' => $clinic_name, 'microsite_website' => $microsite_website], function ($message) {
            $message->from(env('NOREPLY_EMAIL'), 'Microsite-CRTX');
            $message->to(env('SUPPORT_EMAIL'));
            $message->subject('Domain Verification');
        });

        $clinic->domain_verification = Clinic::DNS_STATUS_VERIFICATION_PENDING;
        $clinic->save();

        return response()->json(['message' => 'A verification email has been sent.']);
    }
}
