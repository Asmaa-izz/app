<?php
return [
    'custom' => [
        'name' => [
            'required' => 'The name field is required.',
        ],
        'email' => [
            'email' => 'Please enter a valid email address.',
        ],
        'image' => [
            'max' => 'Image must not exceed 2MB.',
            'mimes' => 'Image must be jpeg, png, jpg or gif.',
        ],
    ],
];

