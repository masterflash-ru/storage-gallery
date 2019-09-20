<?php
/*

*/

namespace Mf\StorageGallery\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * помощник
 */
class GalleryLib extends AbstractHelper 
{
	protected $GalleryLib;

    /**
    * собственно помощник
    */
    public function __invoke(string $razdel="",int $razdel_id=0,int $index=0, string $img_name="")
    {
        if (empty($razdel) && empty($razdel_id)){
            return $this;
        }
        $images=$this->GalleryLib->getItemsArray($razdel,(int)$razdel_id,$index,$img_name, true);
       \Zend\Debug\Debug::dump($images);
        
        return "";
        
        if (empty($image)) {return $default_image;}
        $view=$this->getView();
        return $view->basePath($image);
    }



    public function __construct ($GalleryLib)
    {
        $this->GalleryLib=$GalleryLib;
    }

}
