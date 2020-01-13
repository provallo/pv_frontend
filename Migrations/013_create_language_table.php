<?php

namespace ProVallo\Plugins\Frontend\Migrations;

use ProVallo\Components\Database\Migration;

class Migration_13 extends Migration
{
    
    public function up ()
    {
        $this->addSQL('
            CREATE TABLE `language` (
              `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `name` VARCHAR(255) NOT NULL,
              `isoCode` VARCHAR(16) NOT NULL,
              `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `changed` DATETIME
            );

            INSERT INTO `language` (name, isoCode, created, changed) VALUES
              ("English", "en", NOW(), NOW());
        ');
    }
    
    public function down ()
    {
        // TODO: Implement down() method.
    }
    
}