<?php

namespace ProVallo\Plugins\Frontend\Migrations;

use ProVallo\Components\Database\Migration;

class Migration_22 extends Migration
{
    
    public function up ()
    {
        $this->addSQL('
            ALTER TABLE `snippet_value`
                ADD COLUMN `domainID` INT(11) NOT NULL AFTER `snippetID`;
        ');
    }
    
    public function down ()
    {
        // TODO: Implement down() method.
    }
    
}