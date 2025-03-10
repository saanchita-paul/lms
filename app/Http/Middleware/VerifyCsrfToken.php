<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'callrail','smswebhook/*','verifysms', 'verifysms/resend', 'calendly','nexhealth', 'callrailpostcall','team-member','fb-message','/api/v1/upload-image','calls','getPractiseInfo','create-patient','whereby','save-thread-id','crtxaiwebhook/*'
    ];
}
