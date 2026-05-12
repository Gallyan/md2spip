<?php

declare(strict_types=1);

return [
    'editor_name' => env('LEGAL_EDITOR_NAME', 'Votre Nom / Raison sociale'),
    'editor_siret' => env('LEGAL_EDITOR_SIRET', 'XXX XXX XXX XXXXX'),
    'editor_vat' => env('LEGAL_EDITOR_VAT'),
    'editor_address' => env('LEGAL_EDITOR_ADDRESS', 'Adresse complète'),
    'editor_phone' => env('LEGAL_EDITOR_PHONE', '+33 X XX XX XX XX'),

    'contact_email' => env('LEGAL_CONTACT_EMAIL', 'contact@example.com'),

    'social' => [
        'github' => env('SOCIAL_GITHUB'),
        'website' => env('SOCIAL_WEBSITE', 'https://example.com'),
    ],

    'hosting' => [
        'name' => env('LEGAL_HOSTING_NAME', "Nom de l'hébergeur"),
        'address' => env('LEGAL_HOSTING_ADDRESS', 'Adresse complète'),
        'phone' => env('LEGAL_HOSTING_PHONE', 'numéro'),
        'siret' => env('LEGAL_HOSTING_SIRET', 'XXX XXX XXX XXXXX'),
    ],
];
