<?php

return [
    'smallThumb' => [
        'fit' => [
            'width' => 50,
            'height' => 50,
            'callback' => function ($constraint) {
                $constraint->upsize();
            },
        ],
    ],
];
