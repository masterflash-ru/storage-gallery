<?php
namespace Application;

use Admin\Service\JqGrid\ColModelHelper;
use Admin\Service\JqGrid\NavGridHelper;
use Laminas\Json\Expr;



return [
        /*jqgrid - сетка*/
        "type" => "ijqgrid",
        //"description"=>"Редактирование галерей",
        "options" => [
            "container" => "storage_gallery",
            "caption" => "",
            "podval" => "",
            
            
            /*все что касается чтения в таблицу*/
            "read"=>[
                "db"=>[//плагин выборки из базы
                    "sql"=>"select storage_gallery.*, id as img from storage_gallery where razdel_id=:razdel_id and razdel=':razdel'",
                    "PrimaryKey"=>"id",
                ],
            ],
            /*редактирование*/
            "edit"=>[
                "cache" =>[
                    "tags"=>["storage_gallery","storage_gallery"],
                    "keys"=>["storage_gallery","storage_gallery"],
                ],
                "db"=>[ 
                    "sql"=>"select * from storage_gallery",
                    "PrimaryKey"=>"id",
                ],
            ],
            "add"=>[
                "db"=>[ 
                    "sql"=>"select * from storage_gallery",
                    "PrimaryKey"=>"id",
                ],
                "cache" =>[
                    "tags"=>["storage_gallery","storage_gallery"],
                    "keys"=>["storage_gallery","storage_gallery"],
                ],
            ],
            //удаление записи
            "del"=>[
                "cache" =>[
                    "tags"=>["storage_gallery","storage_gallery"],
                    "keys"=>["storage_gallery","storage_gallery"],
                ],
                "db"=>[ 
                    "sql"=>"select * from storage_gallery",
                    "PrimaryKey"=>"id",
                ],
            ],
            /*внешний вид*/
            "layout"=>[
                "caption" => "Фото",
                "height" => "auto",
                "width" => 800,
                "rowNum" => 10,
                "rowList" => [10,20],
                "sortname" => "poz",
                "sortorder" => "desc",
                "viewrecords" => true,
                "autoencode" => false,
                "hidegrid" => false,
                "toppager" => true,
                
                /*дает доп строку в конце сетки, из данных туда можно ставить итоги какие-либо*/
                //"footerrow"=> true, 
                //"userDataOnFooter"=> true,
               
                // "multiselect" => true,
                //"onSelectRow"=> new Expr("editRow"), //клик на строке вызов строчного редактора
        
                
                "rownumbers" => false,
                "navgrid" => [
                    "button" => NavGridHelper::Button(["search"=>false]),
                    "editOptions"=>NavGridHelper::editOptions(["closeAfterEdit"=>false]),
                    "addOptions"=>NavGridHelper::addOptions(),
                    "delOptions"=>NavGridHelper::delOptions(),
                    "viewOptions"=>NavGridHelper::viewOptions(),
                    "searchOptions"=>NavGridHelper::searchOptions(),
                ],
                "colModel" => [


                    ColModelHelper::text("poz",["label"=>"Порядок","width"=>100,"editoptions" => ["size"=>120 ]]),
                    ColModelHelper::checkbox("public",["label"=>"Публ","width"=>30]),
                    
                    ColModelHelper::image("img",
                                          ["label"=>"Фото",
                                           "width"=>450,
                                           "plugins"=>[
                                               "read"=>[
                                                   "Images" =>[
                                                       "image_id"=>"id",                        //имя поля с ID
                                                       "storage_item_name"=>$_GET["storage_item_name"] ?? "",
                                                       "storage_item_rule_name"=>$_GET["storage_item_rule_name"]  ?? "",   //имя правила из хранилища
                                                   ],
                                               ],
                                               "edit"=>[
                                                   "Images" =>[
                                                       "image_id"=>"id",                        //имя поля с ID
                                                       "storage_item_name"=>$_GET["storage_item_name"] ?? "",
                                                   ],
                                               ],
                                               "del"=>[
                                                   "Images" =>[
                                                       "image_id"=>"id",                        //имя поля с ID
                                                       "storage_item_name"=>$_GET["storage_item_name"] ?? "",
                                                   ],
                                               ],
                                               "add"=>[
                                                   "Images" =>[
                                                       "image_id"=>"id",                        //имя поля с ID
                                                       "storage_item_name"=>$_GET["storage_item_name"] ?? "",
                                                       "database_table_name"=>"storage_gallery"
                                                   ],
                                               ],
                                           ],
                                          ]),
                    
                    //ColModelHelper::hidden("razdel_id"),

                ColModelHelper::cellActions(),
                    
                
                ],
            ],
        ],
];