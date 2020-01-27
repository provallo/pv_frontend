<?php

namespace ProVallo\Plugins\Frontend\Migrations;

use ProVallo\Components\Database\Migration;

class Migration_20 extends Migration
{
    
    public function up ()
    {
        $this->addSQL('
            CREATE TABLE `snippet` (
              `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `name` VARCHAR(255) NOT NULL
            );
        ');
    
        $this->addSQL('
            CREATE TABLE `snippet_value` (
              `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `snippetID` INT(11) NOT NULL,
              `languageID` INT(11) NOT NULL,
              `value` VARCHAR(255) NOT NULL
            );
        ');
    }
    
    public function down ()
    {
        // TODO: Implement down() method.
    }
    
}