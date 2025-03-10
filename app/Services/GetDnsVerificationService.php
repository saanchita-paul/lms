<?php

namespace App\Services;


use App\Models\Clinic;

class GetDnsVerificationService
{
    public function getDnsVerification($id)
    {
        $clinic = Clinic::findOrFail($id);

        return [
            'domain_verification' => $clinic->domain_verification,
        ];
    }
}
