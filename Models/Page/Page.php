<?php

namespace ProVallo\Plugins\Frontend\Models\Page;

use Favez\Mvc\ORM\Entity;
use ProVallo\Plugins\Frontend\Models\Domain\Domain;
use Validator\Validator;

class Page extends Entity
{
    
    const SOURCE = 'page';
    
    const TYPE_CONTENT = 1;
    
    const TYPE_LINK_EXTERNAL = 2;
    
    public $id;
    
    public $parentID;
    
    public $domainID;
    
    public $active;
    
    public $route;
    
    public $label;
    
    public $type;
    
    public $data;
    
    public $position;
    
    public $created;
    
    public $changed;
    
    public function validate ()
    {
        Validator::addGlobalRule('domain.exists', function ($fields, $value, $params) {
            $domainID = (int) $fields['domainID']['value'];
            $domain   = Domain::repository()->find($domainID);
            
            return $domain instanceof Domain;
        });
        
        return [
            'domainID' => [
                'required'      => 'The page must be associated to a domain',
                'domain.exists' => 'The associated domain does not exist'
            ]
        ];
    }
    
}