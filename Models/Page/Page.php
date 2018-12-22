<?php

namespace ProVallo\Plugins\Frontend\Models\Page;

use Favez\Mvc\ORM\Entity;

class Page extends Entity
{
    
    const SOURCE = 'page';
    
    const TYPE_CONTENT = 1;
    
    const TYPE_LINK_EXTERNAL = 2;
    
    public $id;
    
    public $parentID;
    
    public $active;
    
    public $route;
    
    public $label;
    
    public $type;
    
    public $data;
    
    public $position;
    
    public $created;
    
    public $changed;
    
}