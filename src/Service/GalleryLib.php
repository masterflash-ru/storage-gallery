<?php
namespace Mf\StorageGallery\Service;

use Exception;


/*
CREATE TABLE `storage_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `razdel` char(50) NOT NULL COMMENT 'Имя раздела',
  `razdel_id` int(11) NOT NULL DEFAULT '0' COMMENT 'ID раздела',
  `gallery_index` int(11) NOT NULL DEFAULT '0' COMMENT 'номер галереи, начиная с 0',
  `date_public` datetime DEFAULT NULL COMMENT 'дата публикации',
  `public` int(11) DEFAULT NULL COMMENT 'флаг публикации',
  `poz` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `date_public` (`date_public`),
  KEY `razdel` (`razdel`,`razdel_id`),
  KEY `public` (`public`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='галерея в хранилище';

*/


class GalleryLib 
{
	protected $storage;
    protected $connection;
    protected $cache;
    
    /*
    */
    public function __construct($storage,$connection,$cache) 
    {
        $this->storage=$storage;
        $this->connection=$connection;
        $this->cache=$cache;
    }


    /**
    * получить массив номеров галерей для данного имени и раздела
    * $razdel - имя раздела, например, news
    * $razdel_id - идентификатор раздела
    * возвращает массив
    */
    public function getIndexArray(string $razdel,int $razdel_id)
    {
        $rs=$this->connection->Execute("
                select gallery_index from 
                    storage_gallery 
                        where 
                                razdel='$razdel' and 
                                razdel_id='$razdel_id' 
                                    order by gallery_index");
        $rez=[];
        while (!$rs->EOF){
            $rez[]=(int)$rs->Fields->Item["gallery_index"]->Value;
            $rs->MoveNext();
        }
        return $rez;
    }


    /**
    * получить массив выбранной галереи
    * $razdel - имя раздела, например, news
    * $razdel_id - идентификатор раздела
    * $index - номер галереи
    * возвращает массив
    */
    public function getItemsArrayForName(string $razdel,int $razdel_id, int $index=0,string $img_name="admin_img")
    {
        $rs=$this->connection->Execute("
                select * from 
                    storage_gallery 
                        where 
                            razdel='$razdel' and 
                            razdel_id='$razdel_id' and 
                            gallery_index=$index
                                order by poz desc");
        $rez=[];
        while (!$rs->EOF){
            $rez[(int)$rs->Fields->Item["id"]->Value]=$this->storage->loadFile("{$razdel}_{$razdel_id}",(int)$rs->Fields->Item["id"]->Value,$img_name);
            $rs->MoveNext();
        }
        return $rez;
    }
}
