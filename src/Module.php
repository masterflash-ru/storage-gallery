<?php
/**
Библиотека работы с изображениями
 */

namespace Mf\StorageGallery;

class Module
{

public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

}
