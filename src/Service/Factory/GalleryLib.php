<?php
namespace Mf\StorageGallery\Service\Factory;

use Interop\Container\ContainerInterface;

/*
Фабрика 
генерации сервиса обработки файлов/фото и записи/возврата в хранилище
*/

class GalleryLib
{

public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
		$storage=$container->get("FilesLib");
        return new $requestedName($storage);
    }
}

