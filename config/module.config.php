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
            View\Helper\GalleryLib::class => View\Helper\Factory\GalleryLib::class,
        ],
        'aliases' => [
            'GalleryLib' => View\Helper\GalleryLib::class,
            'Gallerylib' => View\Helper\GalleryLib::class,
            'gallerylib' => View\Helper\GalleryLib::class,
        ],
    ],
    /*сетка для админки*/
    "interface"=>[
        "storage-gallery" =>__DIR__."/admin.storage-gallery.php",
    ],

];
