<?php
return [
    'gravatar' => [
        'base_url' => 'https://www.gravatar.com/avatar/',
        'default_size' => 200,
        'default_image' => 'mp', 
    ],

    'pagination' => [
        'per_page' => 15,
    ],

    'user' => [
        'status_active' => 0,
        'status_unverified' => 1,
    ],

    'verification' => [
        'link_expiration_minutes' => 60,
    ],
];
