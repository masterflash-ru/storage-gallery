<?php
namespace Mf\StorageGallery\Service;

use Exception;


/*

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



}
