<?php
// Global configuration values.
return [
    'app_name' => 'SanaWell Product Delivery',
    'base_url' => '',
    'session_timeout' => 1800, // 30 minutes
    'csrf_token_name' => '_csrf_token',
    'roles' => [
        'SUPER_ADMIN' => 'super_admin',
        'AGENCY_ADMIN' => 'agency_admin',
        'OFFICE_STAFF' => 'office_staff',
        'DRIVER' => 'driver',
    ],
];
