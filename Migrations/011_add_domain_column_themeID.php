<?php

namespace ProVallo\Plugins\Frontend\Migrations;

use ProVallo\Components\Database\Migration;

class Migration_11 extends Migration
{
    
    public function up ()
    {
        $this->addSQL('
            ALTER TABLE `domain`
              ADD COLUMN `themeID` INT(11)
              AFTER `id`;
        ');
    }
    
    public function down ()
    {
        // TODO: Implement down() method.
    }
    
}