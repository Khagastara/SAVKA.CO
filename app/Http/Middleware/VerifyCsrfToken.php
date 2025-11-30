<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Authentication
        'login',
        'logout',

        // Products
        'products',
        'products/*',

        // Shipments
        'shipments',
        'shipments/*',

        // Productions
        'productions',
        'productions/*',

        // Materials
        'materials',
        'materials/*',
        'procurements',

        // Staff
        'staff',
        'staff/*',

        // Account
        'account/*',

        // Reports
        'owner/report/*',

        // Forecasting
        'forecasting',
        'forecasting/*',
    ];
}
