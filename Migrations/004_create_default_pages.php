<?php

namespace ProVallo\Plugins\Frontend\Migrations;

use ProVallo\Components\Database\Migration;

class Migration_4 extends Migration
{
    
    public function up ()
    {
        $this->addSQL('
            INSERT INTO `page` (parentID, active, route, main, label, type, `data`, position) VALUES
              (-1, 1, "/", 1, "Home", 1, "Hello Home", 1),
              (-1, 1, "/projects", 1, "Projects", 1, "Hello Projects", 2);
        ');
    }
    
    public function down ()
    {
        // TODO: Implement down() method.
    }
    
}