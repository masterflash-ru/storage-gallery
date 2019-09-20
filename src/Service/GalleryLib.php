<?php
namespace Mf\StorageGallery\Service;

use Mf\StorageGallery\Exception;
use ADO\Service\RecordSet;



class GalleryLib 
{
	protected $storage;
    protected $connection;
    protected $cache;
    protected $config=[];
    
    /**
    * имя ключа из items для обработки галереи
    */
    protected $storage_name="";

    /**
    * дополнительные поля для записи к элементу галереи (подпись, дата публикации.....)
    */
    protected $metas=[
        "alt"=>"",
        "poz"=>0,
        "date_public"=>null
    ];

    /**
    * номер галереи для текущих операций по умолчанию
    */
    protected $index=0;

    /**
    * имя раздела публикации к которой принадлежит галерея, например, news
    */
    protected $razdel="";

    /**
    * ID раздела публикации к которой принадлежит галерея, например, 123
    */
    protected $razdel_id=0;
    
    
    /*
    */
    public function __construct($storage,$connection,$cache,$config) 
    {
        $this->storage=$storage;
        $this->connection=$connection;
        $this->cache=$cache;
        $this->config=$config;
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
    * Создание библиотеки файлов по результатам информации, переданной в setStorageItem
    * на входе имя файла, который находится во временном хранилище БЕЗ ПУТИ!
    * $filename = имя файла из которого создаются все размеры, обычно в data/images
    * $gallery_item_id - ID ЭЛЕМЕНТА галереи куда пишем/замещаем файл, если добавляем новую, тогда 0
    * $options - массив опций (все элементы не обязательны и перезаписывают аналогичные раннее установленные:
    *  [
    *    "storage_name" => "",
    *    "razdel"      => "",
    *    "razdel_id"   => 123,
    *    "index"       =>0,
    *    "metas"      =>[
    *         "alt"=>"",
    *         "poz"=>"",
    *         "date_public"=>"",
    *      ]
    *  ]
    */
    public function saveFiles(string $filename,int $gallery_item_id, array $options=[])
    {
        foreach (["storage_name","razdel","razdel_id","index"] as $k){
            if (isset($options[$k])){
                $$k=$options[$k];
            } else {
                $$k=$this->$k;
            }
        }
        $metas=$this->metas;
        if (isset($options["metas"])){
            if (!is_array($options["metas"])){
                throw new  Exception\InvalidOptionsException("Недопустипые опции для saveFiles");
            }
            foreach (array_keys($this->metas) as $k){
                if (isset($options["metas"][$k])){
                    $metas[$k]=$options["metas"][$k];
                }
            }
        }
        
        $rs=new RecordSet();
        $rs->CursorType = adOpenKeyset;
        $rs->open("SELECT * FROM storage_gallery where id={$gallery_item_id}",$this->connection);
        if ($rs->EOF){
            if (!$storage_name){
                throw new  Exception\ItemNotSetException("Не выбрана секция items из хранилища, используйте метод setStorageItem('ключ_из_items')");
            }
            if (!$razdel){
                throw new  Exception\RazdelNotSetException("Не установлен раздел принадлежности элемента галереи");
            }
            //новая
            $rs->AddNew();
            $rs->Fields->Item["razdel"]->Value=$razdel;
            $rs->Fields->Item["razdel_id"]->Value=$razdel_id;
            $rs->Fields->Item["gallery_index"]->Value=$index;
            $rs->Fields->Item["storage_item_name"]->Value=$storage_name;
        }
        $rs->Fields->Item["alt"]->Value=$metas["alt"];
        $rs->Fields->Item["poz"]->Value=$metas["poz"];
        $rs->Fields->Item["date_public"]->Value=$metas["date_public"];

        $rs->Update();
        $this->storage->selectStorageItem($this->storage_name);
        $this->storage->saveFiles($filename,$this->storage_name,$rs->Fields->Item["id"]->Value);
    }

    
    
    /**
    * обновление метаданных для элемента галереи
    * $gallery_item_id - идентификатор элемента галереи
    * $metas - матаданные (poz, poz, date_public) - ассоциативный массив
    */
    public function updateMeta(int $gallery_item_id, array $metas=[])
    {
        $rs=new RecordSet();
        $rs->CursorType = adOpenKeyset;
        $rs->open("SELECT * FROM storage_gallery where id={$gallery_item_id}",$this->connection);
        if ($rs->EOF){
            throw new  Exception\GalleryItemNotFoundException("Элемент галереи $gallery_item_id не найден");
        }
        $_metas=$this->metas;
        if (!is_array($metas)){
            throw new  Exception\InvalidOptionsException("Недопустипые опции метаданных, должен быть массив");
        }
        foreach (array_keys($this->metas) as $k){
            if (isset($metas[$k])){
                $_metas[$k]=$metas[$k];
            }
        }
        $rs->Fields->Item["alt"]->Value=$_metas["alt"];
        $rs->Fields->Item["poz"]->Value=$_metas["poz"];
        $rs->Fields->Item["date_public"]->Value=$_metas["date_public"];
        $rs->Update();
    }
    
    
    
    
    /**
    * получить массив выбранной галереи
    * $razdel - имя раздела, например, news
    * $razdel_id - идентификатор раздела
    * $index - номер галереи
    * $img_name - имя элемента изображения
    * возвращает массив
    */
    public function getItemsArray(string $razdel,int $razdel_id, int $index=0,string $img_name="admin_img", bool $public_only=true)
    {
        if ($public_only){
            $sql="and public>0";
        } else {
            $sql="";
        }
        $rs=$this->connection->Execute("
                select * from 
                    storage_gallery 
                        where 
                            razdel='{$razdel}' and 
                            razdel_id='{$razdel_id}' and 
                            gallery_index={$index}  {$sql}
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
    
    /*
    * активирует из списка элементов обработчиков хранилища нужный элемент, 
    * передается ключ к массиву для items
    */
    public function setStorageItem(string $name)
    {
        if (!array_key_exists($name,$this->config["storage"]["items"])){
            throw new  Exception\ItemNotFoundException("Запись в {$name} не найдена среди элементов items в конфиге storage");
        }
       $this->storage_name=$name;
    }
    
    /**
    * получить текущий item элемент
    */
    public function getStorageItem()
    {
        return $this->storage_name;
    }
    
    /*
    * установить имя раздела которой принадлежит галерея 
    */
    public function setRazdel(string $name)
    {
       $this->razdel=$name;
    }
    
    /**
    * получить текущий item элемент
    */
    public function getRazdel()
    {
        return $this->razdel;
    }

    /*
    * установить ID раздела которой принадлежит галерея 
    */
    public function setRazdelId(int $razdel_id)
    {
       $this->razdel_id=$razdel_id;
    }
    
    /**
    * получить текущий item элемент
    */
    public function getRazdelId()
    {
        return $this->razdel_id;
    }

    /*
    * установить номер галереи
    */
    public function setIndex(int $index=0)
    {
       $this->index=$index;
    }
    
    /**
    * получить текущий item элемент
    */
    public function getIndex()
    {
        return $this->index;
    }

    /*
    * установить доп. поля для эл-та галереи
    */
    public function setMeta(string $name, string $value)
    {
        if (!array_key_exists($name,$this->metas)){
            throw new  Exception\FieldNotFoundException("Поле {$name} не найдено для элемента галереи");
        }
       $this->metas[$name]=$value;
    }
    
    /**
    * получить текущий item элемент
    */
    public function getMeta(string $name)
    {
        if (!array_key_exists($name,$this->metas)){
            throw new  Exception\FieldNotFoundException("Поле {$name} не найдено для элемента галереи");
        }
        return $this->metas[$name];
    }

}
