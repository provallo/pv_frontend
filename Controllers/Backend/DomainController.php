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
    
    protected function checkPermission (Entity $entity, $action)
    {
        switch ($action)
        {
            case 'remove':
                $pages = (int) self::db()->from('page')->where('domainID = ?', $entity->id)->count();
                
                return $pages === 0;
            break;
        }
    }
    
}