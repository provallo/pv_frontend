<?php

namespace ProVallo\Plugins\Frontend\Models\Snippet;

use Favez\Mvc\ORM\Entity;

class Snippet extends Entity
{
    
    const SOURCE = 'snippet';
    
    public $id;
    
    public $name;
    
    public function initialize ()
    {
        $this->hasMany(Value::class, 'snippetID', 'id')->setName('values');
    }
    
}