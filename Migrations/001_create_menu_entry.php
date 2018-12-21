<?php

namespace ProVallo\Plugins\Frontend\Migrations;

use ProVallo\Components\Database\Migration;

class Migration_1 extends Migration
{
    
    public function up ()
    {
        $this->addSQL('
            INSERT INTO `menu` (parentID, label, route, position) VALUES
              (-1, "Pages", "/pages", 2);
        ');
    }
    
    public function down ()
    {
        // TODO: Implement down() method.
    }
    
}