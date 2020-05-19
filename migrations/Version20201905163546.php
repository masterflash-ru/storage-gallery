<?php

namespace Mf\StorageGallery;

use Mf\Migrations\AbstractMigration;
use Mf\Migrations\MigrationInterface;
use Laminas\Db\Sql\Ddl;

class Version20201905163546 extends AbstractMigration implements MigrationInterface
{
    public static $description = "Create table for Storage-gallery";

    public function up($schema, $adapter)
    {
        $table = new Ddl\CreateTable("storage_gallery");
        $table->addColumn(new Ddl\Column\Integer('id',false,null,["AUTO_INCREMENT"=>true]));
        $table->addColumn(new Ddl\Column\Char('razdel', 50,false,null,["COMMENT"=>"раздел, например, news"]));
        $table->addColumn(new Ddl\Column\Integer('razdel_id',false,null,["COMMENT"=>"ID раздела, например, 10"]));
        $table->addColumn(new Ddl\Column\Integer('todelete',true,0,["COMMENT"=>"флаг что нужно удалить эти фото"]));
        $table->addColumn(new Ddl\Column\Char('storage_item_name',100,true,null,["COMMENT"=>"Имя эл-та хранилища обработки отдельных фото галереи"]));
        $table->addColumn(new Ddl\Column\Datetime('date_public', true,null,["COMMENT"=>"дата публикации"]));
        $table->addColumn(new Ddl\Column\Char('alt', 255,true,null,["COMMENT"=>"подпись фото"]));
        $table->addColumn(new Ddl\Column\Char('url', 255,true,null,["COMMENT"=>"URL"]));
        $table->addColumn(new Ddl\Column\Integer('public',false,null));
        $table->addColumn(new Ddl\Column\Integer('poz',true,0,["COMMENT"=>"Порядок"]));

        $table->addConstraint(
            new Ddl\Constraint\PrimaryKey(['id'])
        );
        $table->addConstraint(
            new Ddl\Index\Index(['razdel_id','razdel'])
        );
        $table->addConstraint(
            new Ddl\Index\Index(['todelete'])
        );
        $table->addConstraint(
            new Ddl\Index\Index(['public'])
        );
        $table->addConstraint(
            new Ddl\Index\Index(['date_public'])
        );
       
        $this->addSql($table);
    }

    public function down($schema, $adapter)
    {
        $drop = new Ddl\DropTable('storage_gallery');
        $this->addSql($drop);
    }
}
