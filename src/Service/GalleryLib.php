<?php
namespace Mf\StorageGallery\Service;

use Exception;
use ADO\Service\RecordSet;



class GalleryLib 
{
	protected $storage;
    protected $connection;
    protected $cache;
    protected $storage_name="";
    
    /*
    */
    public function __construct($storage,$connection,$cache) 
    {
        $this->storage=$storage;
        $this->connection=$connection;
        $this->cache=$cache;
    }

    /*
    * активирует из списка элементов обработчиков хранилища нужный элемент, 
    * передается ключ к массиву для items
    */
    public function selectStorageItem(string $name)
    {
       $this->storage_name=$name;
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
    * Создание библиотеки файлов по результатам информации, переданной в selectStorageItem
    * на входе имя файла, который находится во временном хранилище БЕЗ ПУТИ!
    * $filename = имя файла из которого создаются все размеры, обычно в data/images
    * $gallery_id - ID галереи куда пишем/замещаем файл, если добавляем новую, тогда 0
    * $razdel - имя раздела, например, news
    * $razdel_id - уникальный номер записи, обычно ID записи, например в новостях
    * $index - номер галереи, по умолчанию 0
    * $field_values - дополнительные поля для записи
    */
    public function saveFiles(string $filename,int $gallery_id, string $razdel, int $razdel_id, int $index=0, array $field_values=[])
    {
        $rs=new RecordSet();
        $rs->CursorType = adOpenKeyset;
        $rs->open("SELECT * FROM storage_gallery where id={$gallery_id}",$this->connection);
        if ($rs->EOF){
            //новая
            $rs->AddNew();
            $rs->Fields->Item["razdel"]->Value=$razdel;
            $rs->Fields->Item["razdel_id"]->Value=$razdel_id;
            $rs->Fields->Item["gallery_index"]->Value=$index;
        }
        if (isset($field_values["alt"])){
            $rs->Fields->Item["alt"]->Value=$field_values["alt"];
        }
        if (isset($field_values["poz"])){
            $rs->Fields->Item["poz"]->Value=$field_values["poz"];
        }
        if (isset($field_values["date_public"])){
            $rs->Fields->Item["poz"]->Value=$field_values["date_public"];
        }
        $rs->Update();
        $this->storage->selectStorageItem($this->storage_name);
        $this->storage->saveFiles($filename,$this->storage_name,$rs->Fields->Item["id"]->Value);
    }

    /**
    * получить массив выбранной галереи
    * $razdel - имя раздела, например, news
    * $razdel_id - идентификатор раздела
    * $index - номер галереи
    * $img_name - имя элемента изображения
    * возвращает массив
    */
    public function getItemsArray(string $razdel,int $razdel_id, int $index=0,string $img_name="admin_img")
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
            $img=[
                "img"=>$this->storage->loadFile($rs->Fields->Item["storage_gallery_name"]->Value,(int)$rs->Fields->Item["id"]->Value,$img_name),
                "alt"=>$rs->Fields->Item["alt"]->Value,
                "date_public"=>$rs->Fields->Item["date_public"]->Value,
                "public"=>$rs->Fields->Item["public"]->Value,
                "poz"=>$rs->Fields->Item["poz"]->Value,
            ];
            
            
            $rez[(int)$rs->Fields->Item["id"]->Value]=$img;
            $rs->MoveNext();
        }
        return $rez;
    }
}
