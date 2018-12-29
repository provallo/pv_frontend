<?php

namespace ProVallo\Plugins\Frontend\Migrations;

use ProVallo\Components\Database\Migration;

class Migration_8 extends Migration
{
    
    public function up ()
    {
        $this->addSQL('
            ALTER TABLE `page`
              ADD COLUMN `domainID` INT(11) NOT NULL
              AFTER `parentID`;
        ');
    }
    
    public function down ()
    {
        // TODO: Implement down() method.
    }
    
}