<?php

namespace ProVallo\Plugins\Frontend\Migrations;

use ProVallo\Components\Database\Migration;

class Migration_19 extends Migration
{
    
    public function up ()
    {
        $this->addSQL('
            CREATE TABLE `page_translation` (
              `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `pageID` INT(11) NOT NULL,
              `languageID` INT(11) NOT NULL,
              `label` VARCHAR(255) NOT NULL,
              `title` VARCHAR(255) NOT NULL,
              `data` BLOB NULL
            );
        ');
    }
    
    public function down ()
    {
        // TODO: Implement down() method.
    }
    
}