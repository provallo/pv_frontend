<?php

namespace ProVallo\Plugins\Frontend\Migrations;

use ProVallo\Components\Database\Migration;
use ProVallo\Core;

class Migration_17 extends Migration
{
    
    public function up ()
    {
        $languageID = Core::db()->from('language')->fetchColumn(0);
        
        $this->addSQL('
            UPDATE domain
            SET languageID = :languageID
        ', compact('languageID'));
    }
    
    public function down ()
    {
        // TODO: Implement down() method.
    }
    
}