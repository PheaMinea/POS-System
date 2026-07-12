<?php

return [
    'url' => rtrim(
        env('API_URL', env('APP_URL', 'http://127.0.0.1:8000') . '/api'),
        '/'
    ),
];
