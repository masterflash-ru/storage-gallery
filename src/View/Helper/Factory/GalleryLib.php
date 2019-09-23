<?php
namespace Mf\StorageGallery\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


/**
 * универсальная фабрика для помощника
 * 
 */
class GalleryLib implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
	   $GalleryLib=$container->get("GalleryLib");
         $cache = $container->get('DefaultSystemCache');
        return new $requestedName($GalleryLib,$cache);
    }
}

