<?php

return [
    'smallThumb' => [
        'resize' => [
            'width' => 50,
            'height' => null,
            'callback' => function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            },
        ],
    ],
    'mediumThumb' => [
        'resize' => [
            'width' => 180,
            'height' => null,
            'callback' => function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            },
        ],
    ],
];
