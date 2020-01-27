<?php

namespace ProVallo\Plugins\Frontend\Models\Snippet;

use Favez\Mvc\ORM\Entity;

class Value extends Entity
{
    
    const SOURCE = 'snippet_value';
    
    const SHOULD_REMOVE_WITH_PARENT = true;
    
    const SHOULD_REFRESH_WITH_PARENT = true;
    
    const SHOULD_UPDATE_WITH_PARENT = true;
    
    public $id;
    
    public $snippetID;
    
    public $languageID;
    
    public $value;
    
}