<?php

namespace ProVallo\Plugins\Frontend\Models\Theme;

use Favez\Mvc\ORM\Entity;

class Theme extends Entity
{
    
    const SOURCE = 'theme';
    
    public $id;
    
    public $name;
    
    public $created;
    
}