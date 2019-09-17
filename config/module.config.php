<?php
/**
*библиотека работы с галереями
*/

namespace Mf\StorageGallery;


return [

    'service_manager' => [
        'factories' => [//сервисы-фабрики
            Service\GalleryLib::class => Service\Factory\GalleryLib::class,
        ],
        'aliases' => [
            "GalleryLib"=>Service\GalleryLib::class,
            "Gallerylib"=>Service\GalleryLib::class,
            "gallerylib"=>Service\GalleryLib::class,
        ],
    ],

    'view_helpers' => [
        'factories' => [
        ],
        'aliases' => [
        ],
    ],
];
