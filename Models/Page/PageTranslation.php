<?php

namespace ProVallo\Plugins\Frontend\Models\Page;

use Favez\Mvc\ORM\Entity;

class PageTranslation extends Entity
{
    
    public $id;
    
    public $pageID;
    
    public $languageID;
    
    public $label;
    
    public $title;
    
    public $content;
    
}