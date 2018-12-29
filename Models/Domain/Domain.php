<?php

namespace ProVallo\Plugins\Frontend\Models\Domain;

use Favez\Mvc\ORM\Entity;

class Domain extends Entity
{
    
    const SOURCE = 'domain';
    
    public $id;
    
    public $active;
    
    public $label;
    
    public $host;
    
    public $hosts;
    
    public $secure;
    
    public $changed;
    
    public $created;
    
}