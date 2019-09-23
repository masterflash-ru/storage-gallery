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
    * возвращает массив фото и меиаданных к ним
    * [
        [15116] => array(5) {
        ["img"] => string(60) "/pic/gallery/345_194/02/e7/0b/ca10023a7cb851b7147eebde62.jpg"
        ["alt"] => string(0) ""
        ["date_public"] => string(19) "2018-03-31 07:39:07"
        ["public"] => string(1) "1"
        ["poz"] => NULL
      }
  ]
  *  $razdel - имя раздела
  * $razdel_id - ID раздела
  * $index - номер галереи
  * $img_name - имя ёлемента из хранилища, указанного а конфиге приложения
  * если помощник вызывается без параметров, возвращается сам этот объект
    */
    public function __invoke(string $razdel="",int $razdel_id=0,int $index=0, string $img_name="")
    {
        if (empty($razdel) && empty($razdel_id)){
            return $this;
        }
        $view=$this->getView();
        $images=$this->GalleryLib->getItemsArray($razdel,(int)$razdel_id,$index,$img_name, ["public_only"=>true]);
        foreach ($images as &$img){
            $img["img"]=$view->basePath($img["img"]);
        }
        return $images;
    }



    public function __construct ($GalleryLib)
    {
        $this->GalleryLib=$GalleryLib;
    }

}
