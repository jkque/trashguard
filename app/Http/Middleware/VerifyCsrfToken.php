<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/m/api/register',
        '/m/api/login',
        '/m/api/report/send',
        '/m/api/user/upload',
        '/m/api/report/getReports',
        '/m/api/report/getReportsCount',
        '/m/api/user/getProfilePicture',
        '/m/sendNotification',
        '/m/api/logout'
    ];
}
