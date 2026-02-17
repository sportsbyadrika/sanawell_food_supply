<?php
// Global configuration values.
return [
    'app_name' => 'SanaWell Product Delivery',
    'base_url' => '',
    'session_timeout' => 1800, // 30 minutes
    'csrf_token_name' => '_csrf_token',
     'roles' => [
        'SUPER_ADMIN' => [
            'id'   => 1,
            'slug' => 'super_admin',
        ],
        'AGENCY_ADMIN' => [
            'id'   => 2,
            'slug' => 'agency_admin',
        ],
        'OFFICE_STAFF' => [
            'id'   => 3,
            'slug' => 'office_staff',
        ],
        'DRIVER' => [
            'id'   => 4,
            'slug' => 'driver',
        ],
    ],
];
