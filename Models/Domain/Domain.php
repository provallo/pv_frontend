<?php

namespace ProVallo\Plugins\Frontend\Models\Domain;

use Favez\Mvc\ORM\Entity;
use ProVallo\Plugins\Frontend\Models\Theme\Theme;

class Domain extends Entity
{
    
    const SOURCE = 'domain';
    
    public $id;
    
    public $themeID;
    
    public $active;
    
    public $label;
    
    public $host;
    
    public $hosts;
    
    public $secure;
    
    public $changed;
    
    public $created;
    
    public function initialize ()
    {
        $this->belongsTo(Theme::class, 'themeID', 'id')->setName('theme');
    }
    
}