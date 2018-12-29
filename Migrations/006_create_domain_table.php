<?php

namespace ProVallo\Plugins\Frontend\Migrations;

use ProVallo\Components\Database\Migration;

class Migration_6 extends Migration
{
    
    public function up ()
    {
        $this->addSQL('
            CREATE TABLE `domain` (
              `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `active` tinyint(1) DEFAULT \'1\',
              `label` varchar(255) NOT NULL,
              `host` varchar(255) NOT NULL,
              `hosts` text NOT NULL,
              `secure` tinyint(1) DEFAULT \'0\',
              `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `changed` DATETIME
            )
        ');
    }
    
    public function down ()
    {
        // TODO: Implement down() method.
    }
    
}