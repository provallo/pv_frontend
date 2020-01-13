<?php

namespace ProVallo\Plugins\Frontend\Migrations;

use ProVallo\Components\Database\Migration;
use ProVallo\Core;

class Migration_15 extends Migration
{
    
    public function up ()
    {
        $configID = Core::db()->from('menu')->where('label = ?', 'Config')->fetchColumn(0);
        $domainID = Core::db()->from('menu')->where('label = ?', 'Domains')->fetchColumn(0);
        
        $this->addSQL('
            UPDATE menu SET parentID = :configID, route = "/config/domains" WHERE id = :domainID
        ', compact('configID', 'domainID'));
    }
    
    public function down ()
    {
        // TODO: Implement down() method.
    }
    
}