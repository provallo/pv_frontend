<?php

namespace ProVallo\Plugins\Frontend\Migrations;

use ProVallo\Components\Database\Migration;

class Migration_7 extends Migration
{
    
    public function up ()
    {
        $this->addSQL('
            INSERT INTO `menu` (parentID, label, route, position) VALUES
              (-1, "Domains", "/domains", 1);
        ');
    }
    
    public function down ()
    {
        // TODO: Implement down() method.
    }
    
}