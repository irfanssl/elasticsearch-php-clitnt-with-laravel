<?php

return [
    'host'     => env('ELASTICSEARCH_HOST', 'https://127.0.0.1:9200'),
    'username' => env('ELASTICSEARCH_USERNAME', 'elastic'),
    'password' => env('ELASTICSEARCH_PASSWORD', null)
];
