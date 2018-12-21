<?php

namespace ProVallo\Plugins\Frontend\Migrations;

use ProVallo\Components\Database\Migration;

class Migration_3 extends Migration
{
    
    public function up ()
    {
        $this->addSQL('
            ALTER TABLE `page`
              ADD COLUMN `position` INT(11) DEFAULT 0
              AFTER `data`;
        ');
    }
    
    public function down ()
    {
        // TODO: Implement down() method.
    }
    
}