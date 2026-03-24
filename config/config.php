<?php
// Global configuration values.
return [
     'environment' => 'local',
    'app_name' => 'Dew Route Product Delivery',
    'base_url' => '',
    'session_timeout' => 1800, // 30 minutes
    'csrf_token_name' => '_csrf_token',
     'roles' => [
        'SUPER_ADMIN' => [
            'id'   => 5,
            'slug' => 'super_admin',
        ],
        'AGENCY_ADMIN' => [
            'id'   => 6,
            'slug' => 'agency_admin',
        ],
        'OFFICE_STAFF' => [
            'id'   => 7,
            'slug' => 'office_staff',
        ],
        'DRIVER' => [
            'id'   => 8,
            'slug' => 'driver',
        ],
    ],
];
