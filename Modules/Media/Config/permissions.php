<?php

return [
    'media.medias' => [
        'index' => 'media::media.list resource',
        'create' => 'media::media.create resource',
        'edit' => 'media::media.edit resource',
        'destroy' => 'media::media.destroy resource',
    ],
    'media.folders' => [
        'index' => 'media::folders.list resource',
        'create' => 'media::folders.create resource',
        'edit' => 'media::folders.edit resource',
        'destroy' => 'media::folders.destroy resource',
    ],
];
