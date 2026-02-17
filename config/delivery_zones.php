<?php

return [
    'free' => [
        'label' => 'Free Delivery (Nearby)',
        'min_km' => 0,
        'max_km' => 5,
    ],
    'standard' => [
        'label' => 'Standard Delivery',
        'min_km' => 5,
        'max_km' => 10,
    ],
    'extended' => [
        'label' => 'Extended Delivery',
        'min_km' => 10,
        'max_km' => 20,
    ],
    'blocked' => [
        'label' => 'Out of Service Area',
        'min_km' => 20,
        'max_km' => 999,
    ],
];