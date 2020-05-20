<?php
namespace Application;

use Admin\Service\JqGrid\ColModelHelper;
use Admin\Service\JqGrid\NavGridHelper;
use Laminas\Json\Expr;



return [
        /*jqgrid - сетка*/
        "type" => "ijqgrid",
        "description"=>"Редактирование Услуг",
        "options" => [
            "container" => "storage-gallery",
            "caption" => "",
            "podval" => "",
            
            
            /*все что касается чтения в таблицу*/
            "read"=>[
                "db"=>[//плагин выборки из базы
                    "sql"=>"select uslugi.*, id as img from uslugi",
                    "PrimaryKey"=>"id",
                ],
            ],
            /*редактирование*/
            "edit"=>[
                "cache" =>[
                    "tags"=>["uslugi","uslugi"],
                    "keys"=>["uslugi","uslugi"],
                ],
                "db"=>[ 
                    "sql"=>"select * from uslugi",
                    "PrimaryKey"=>"id",
                ],
            ],
            "add"=>[
                "db"=>[ 
                    "sql"=>"select * from uslugi",
                    "PrimaryKey"=>"id",
                ],
                "cache" =>[
                    "tags"=>["uslugi","uslugi"],
                    "keys"=>["uslugi","uslugi"],
                ],
            ],
            //удаление записи
            "del"=>[
                "cache" =>[
                    "tags"=>["uslugi","uslugi"],
                    "keys"=>["uslugi","uslugi"],
                ],
                "db"=>[ 
                    "sql"=>"select * from uslugi",
                    "PrimaryKey"=>"id",
                ],
            ],
            /*внешний вид*/
            "layout"=>[
                "caption" => "Список услуг",
                "height" => "auto",
                //"width" => 1000,
                "rowNum" => 10,
                "rowList" => [10,20],
                "sortname" => "poz",
                "sortorder" => "asc",
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

                    ColModelHelper::text("name",["label"=>"Услуга","width"=>400,"editoptions" => ["size"=>120 ]]),
                    ColModelHelper::text("poz",["label"=>"Порядок","width"=>100,"editoptions" => ["size"=>120 ]]),
                    ColModelHelper::text("url",[
                        "width"=>400,
                        "hidden"=>true,
                        "editrules"=>[
                            "edithidden"=>true,
                        ],
                        "plugins"=>[
                            "edit"=>[
                                "translit"=>[
                                    "source"=>"name"
                                ],
                            ],
                            "edit"=>[
                                "translit"=>[
                                    "source"=>"name"
                                ],
                            ],
                            "add"=>[
                                "translit"=>[
                                    "source"=>"name"
                                ],
                            ],
                        ],
                       "editoptions" => ["size"=>120 ],
                    ]),


                    ColModelHelper::ckeditor("content1",[
                        "label"=>"Контент перед банерами",
                        "plugins"=>[
                            "edit"=>[
                                "ClearContent"=>[],
                            ],
                            "add"=>[
                                "ClearContent"=>[],
                            ],
                        ],
                    ]),
                    ColModelHelper::ckeditor("content2",[
                        "label"=>"Контент после банеров",
                        "plugins"=>[
                            "edit"=>[
                                "ClearContent"=>[],
                            ],
                            "add"=>[
                                "ClearContent"=>[],
                            ],
                        ],
                    ]),
                    
                    ColModelHelper::image("img",
                                          ["label"=>"Инфографика",
                                           "plugins"=>[
                                               "read"=>[
                                                   "Images" =>[
                                                       "image_id"=>"id",                        //имя поля с ID
                                                       "storage_item_name"=>"uslugi",
                                                       "storage_item_rule_name"=>"img"   //имя правила из хранилища
                                                   ],
                                               ],
                                               "edit"=>[
                                                   "Images" =>[
                                                       "image_id"=>"id",                        //имя поля с ID
                                                       "storage_item_name"=>"uslugi",
                                                   ],
                                               ],
                                               "del"=>[
                                                   "Images" =>[
                                                       "image_id"=>"id",                        //имя поля с ID
                                                       "storage_item_name"=>"uslugi",
                                                   ],
                                               ],
                                               "add"=>[
                                                   "Images" =>[
                                                       "image_id"=>"id",                        //имя поля с ID
                                                       "storage_item_name"=>"uslugi",
                                                       "database_table_name"=>"uslugi"
                                                   ],
                                               ],
                                           ],
                                          ]),

                    ColModelHelper::textarea("title",["label"=>"TITLE","hidden"=>true,"editrules"=>["edithidden"=>true]]),
                    ColModelHelper::textarea("keywords",["label"=>"KEYWORDS","hidden"=>true,"editrules"=>["edithidden"=>true]]),
                    ColModelHelper::textarea("description",["label"=>"DESCRIPTION","hidden"=>true,"editrules"=>["edithidden"=>true]]),

                ColModelHelper::cellActions(),
                    
                
                ],
            ],
        ],
];