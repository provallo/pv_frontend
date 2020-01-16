<?php

namespace ProVallo\Plugins\Frontend\Migrations;

use ProVallo\Components\Database\Migration;

class Migration_18 extends Migration
{
    
    public function up ()
    {
        $this->addSQL('
            CREATE TABLE `domain_language` (
              `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `domainID` INT(11) NOT NULL,
              `languageID` INT(11) NOT NULL
            );
        ');
    }
    
    public function down ()
    {
        // TODO: Implement down() method.
    }
    
}