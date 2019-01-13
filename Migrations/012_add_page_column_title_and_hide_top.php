<?php

namespace ProVallo\Plugins\Frontend\Migrations;

use ProVallo\Components\Database\Migration;

class Migration_12 extends Migration
{
    
    public function up ()
    {
        $this->addSQL('
            ALTER TABLE `page`
              ADD COLUMN `title` VARCHAR(255) NOT NULL AFTER `label`,
              ADD COLUMN `hideTop` INT(11) DEFAULT 0 AFTER `active`
        ');
    }
    
    public function down ()
    {
        // TODO: Implement down() method.
    }
    
}