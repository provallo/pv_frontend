<?php

namespace ProVallo\Controllers\Backend;

use Favez\ORM\Entity\Entity;
use ProVallo\Plugins\Backend\Components\Controllers\API;
use ProVallo\Plugins\Frontend\Models\Domain\Domain;

class DomainController extends API
{
    
    public function configure ()
    {
        return [
            'model' => Domain::class
        ];
    }
    
    protected function setDefaultValues (Entity $entity)
    {
        $entity->created = date('Y-m-d H:i:s');
    }
    
    protected function setValues (Entity $entity, $input)
    {
        $entity->changed = date('Y-m-d H:i:s');
        
        $entity->active = (int) $input['active'];
        $entity->label  = $input['label'];
        $entity->host   = $input['host'];
        $entity->hosts  = $input['hosts'];
        $entity->secure = (int) $input['secure'];
    }
    
}