<?php

namespace ProVallo\Plugins\Frontend\Migrations;

use ProVallo\Components\Database\Migration;

class Migration_16 extends Migration
{
    
    public function up ()
    {
        $this->addSQL('
            ALTER TABLE `domain`
              ADD COLUMN `languageID` INT(11)
              AFTER `id`;
        ');
    }
    
    public function down ()
    {
        // TODO: Implement down() method.
    }
    
}