<?php

declare(strict_types=1);

return [
    'editor_name' => env('LEGAL_EDITOR_NAME', config('app.name')),

    'organization_name' => env('LEGAL_ORGANIZATION_NAME', config('app.name')),
    'editor_address' => env('LEGAL_EDITOR_ADDRESS'),
    'editor_phone' => env('LEGAL_EDITOR_PHONE'),

    'contact_email' => env('LEGAL_CONTACT_EMAIL'),

    'social' => [
        'github' => env('SOCIAL_GITHUB'),
        'website' => env('SOCIAL_WEBSITE'),
    ],

    'hosting' => [
        'name' => env('LEGAL_HOSTING_NAME'),
        'address' => env('LEGAL_HOSTING_ADDRESS'),
        'phone' => env('LEGAL_HOSTING_PHONE'),
        'siret' => env('LEGAL_HOSTING_SIRET'),
    ],
];
