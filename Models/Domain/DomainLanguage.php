<?php

namespace ProVallo\Plugins\Frontend\Models\Domain;

use Favez\Mvc\ORM\Entity;

class DomainLanguage extends Entity
{
    
    const SOURCE                     = 'domain_language';
    
    const SHOULD_REFRESH_WITH_PARENT = true;
    
    const SHOULD_UPDATE_WITH_PARENT  = true;
    
    const SHOULD_REMOVE_WITH_PARENT  = true;
    
    public $id;
    
    public $domainID;
    
    public $languageID;
    
}