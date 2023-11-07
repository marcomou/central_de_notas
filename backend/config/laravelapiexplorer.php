<?php

return [

    'enabled' => env('LARAVEL_API_EXPLORER_ENABLED', false),

    'route' => 'api-explorer',

    'match' => 'api/*',

    'ignore' => [
        '/'
    ],

];