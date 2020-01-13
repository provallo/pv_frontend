<?php

namespace ProVallo\Plugins\Frontend\Models\Language;

use Favez\Mvc\ORM\Entity;

class Language extends Entity
{
    
    const SOURCE = 'language';
    
    public $id;
    
    public $name;
    
    public $isoCode;
    
    public $created;
    
    public $changed;
    
}