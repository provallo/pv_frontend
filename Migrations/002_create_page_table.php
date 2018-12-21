<?php

namespace ProVallo\Plugins\Frontend\Migrations;

use ProVallo\Components\Database\Migration;

class Migration_2 extends Migration
{
    
    public function up ()
    {
        $this->addSQL('
            CREATE TABLE `page` (
              `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `parentID` INT(11) NOT NULL,
              `active` TINYINT(1) DEFAULT 0,
              `route` VARCHAR(255) NOT NULL,
              `main` TINYINT(1) DEFAULT 0,
              `label` VARCHAR(255) NOT NULL,
              `type` INT(11) NOT NULL,
              `data` BLOB,
              `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `changed` DATETIME
            )
        ');
    }
    
    public function down ()
    {
        // TODO: Implement down() method.
    }
    
}