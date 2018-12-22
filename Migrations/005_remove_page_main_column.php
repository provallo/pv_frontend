<?php

namespace ProVallo\Plugins\Frontend\Migrations;

use ProVallo\Components\Database\Migration;

class Migration_5 extends Migration
{
    
    public function up ()
    {
        $this->addSQL('
            ALTER TABLE `page`
              DROP COLUMN `main`;
        ');
    }
    
    public function down ()
    {
        // TODO: Implement down() method.
    }
    
}