<?php
/*

*/

namespace Mf\StorageGallery\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * помощник - выдает массив файлов с мета-данными для генерации галерей
 */
class GalleryLib extends AbstractHelper 
{
	protected $GalleryLib;
    protected $cache;

    /**
    * собственно помощник
    * $razdel - имя раздела, например, news
    * $razdel_id - ID этого раздела
    * $index - номер галереи
    * $img_name - имя изображения, как в ключах конфига хранилища
    */
    public function __invoke(string $razdel="",int $razdel_id=0,int $index=0, string $img_name="")
    {
        if (empty($razdel) && empty($razdel_id) && empty($img_name)){
            return $this;
        }
         $result = false;
         $key="gallery_files_array_{$razdel}_{$razdel_id}_{$index}_{$img_name}";
         $images = $this->cache->getItem($key, $result);
         if (!$result){

            $view=$this->getView();
            $images=$this->GalleryLib->getItemsArray($razdel,(int)$razdel_id,$index,$img_name, false);
            foreach ($images as &$img){
                $img["img"]=$view->basePath($img["img"]);
            }
             $this->cache->setItem($key, $images);
             $this->cache->setTags($key,["storage_gallery"]);

         }
        
        return $images;
        
    }



    public function __construct ($GalleryLib,$cache)
    {
        $this->GalleryLib=$GalleryLib;
        $this->cache=$cache;
    }

}
