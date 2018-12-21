<?php

namespace ProVallo\Plugins\Frontend\Models\Page;

use Favez\Mvc\ORM\Entity;

class Page extends Entity
{
    
    const SOURCE = 'page';
    
    public $id;
    
    public $parentID;
    
    public $active;
    
    public $route;
    
    public $main;
    
    public $label;
    
    public $type;
    
    public $data;
    
    public $position;
    
    public $created;
    
    public $changed;
    
}