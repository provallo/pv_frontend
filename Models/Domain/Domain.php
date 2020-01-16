<?php

namespace ProVallo\Plugins\Frontend\Models\Domain;

use Favez\Mvc\ORM\Entity;
use ProVallo\Plugins\Frontend\Models\Language\Language;
use ProVallo\Plugins\Frontend\Models\Theme\Theme;

class Domain extends Entity
{
    
    const SOURCE = 'domain';
    
    public $id;
    
    public $languageID;
    
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
        $this->hasOne(Theme::class, 'themeID', 'id')->setName('theme');
        $this->hasOne(Language::class, 'languageID', 'id')->setName('language');
        
        $this->manyToMany(
            DomainLanguage::class,
            'domainID',
            'languageID',
            Language::class,
            'id'
        )->setName('languages');
    }
    
}