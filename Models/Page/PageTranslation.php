<?php

namespace ProVallo\Plugins\Frontend\Models\Page;

use Favez\Mvc\ORM\Entity;

class PageTranslation extends Entity
{
    
    const SOURCE = 'page_translation';
    
    const SHOULD_UPDATE_WITH_PARENT = true;
    
    const SHOULD_REMOVE_WITH_PARENT = true;
    
    public $id;
    
    public $pageID;
    
    public $languageID;
    
    public $label;
    
    public $title;
    
    public $data;
    
}