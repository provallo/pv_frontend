<?php

namespace ProVallo\Controllers\Backend;

use Favez\ORM\Entity\Entity;
use ProVallo\Plugins\Backend\Components\Controllers\API;
use ProVallo\Plugins\Frontend\Models\Language\Language;

class LanguageController extends API
{
    
    public function configure ()
    {
        return [
            'model' => Language::class
        ];
    }
    
    protected function map ($row)
    {
        $row['id'] = (int) $row['id'];
        
        return $row;
    }
    
    protected function setDefaultValues (Entity $entity)
    {
        $entity->created = date('Y-m-d H:i:s');
    }
    
    protected function setValues (Entity $entity, $input)
    {
        $entity->changed = date('Y-m-d H:i:s');
        
        $entity->name    = $input['name'];
        $entity->isoCode = $input['isoCode'];
    }
    
    protected function checkPermission (Entity $entity, $action)
    {
        return true;
    }
    
}