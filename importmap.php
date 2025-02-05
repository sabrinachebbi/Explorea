<?php
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    'header' => [
        'path' => './assets/header.js',
        'entrypoint' => true,
    ],
    'acceuil' => [
        'path' => './assets/acceuil.js',
        'entrypoint' => true,
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    '@hotwired/turbo' => [
        'version' => '7.3.0',
    ],
];
