<?php

namespace ProVallo\Plugins\Frontend\Migrations;

use ProVallo\Components\Database\Migration;

class Migration_10 extends Migration
{
    
    public function up ()
    {
        $this->addSQL('
            CREATE TABLE `theme` (
              `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `name` VARCHAR(255) NOT NULL,
              `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            )
        ');
    }
    
    public function down ()
    {
        // TODO: Implement down() method.
    }
    
}