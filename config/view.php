<?php

return [

    'paths' => [
        resource_path('views'),
    ],

    'compiled' => env(
        'VIEW_COMPILED_PATH',
        realpath(storage_path('framework/views'))
    ),

    // 💡 Sem přidáme náš layout:
    'default_layout' => env('DEFAULT_LAYOUT', 'layouts.default.app'),

];
