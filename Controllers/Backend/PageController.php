<?php

namespace ProVallo\Controllers\Backend;

use Favez\ORM\Entity\Entity;
use ProVallo\Plugins\Backend\Components\Controllers\API;
use ProVallo\Plugins\Frontend\Models\Page\Page;

class PageController extends API
{
    
    public function configure ()
    {
        return [
            'model' => Page::class
        ];
    }
    
    protected function setDefaultValues (Entity $entity)
    {
        $entity->created  = date('Y-m-d H:i:s');
        $entity->parentID = -1;
    }
    
    protected function setValues (Entity $entity, $input)
    {
        $entity->changed = date('Y-m-d H:i:s');
        
        $entity->parentID = $input['parentID'];
        $entity->active   = (int) $input['active'];
        $entity->route    = $input['route'];
        $entity->label    = $input['label'];
        $entity->type     = $input['type'];
        $entity->data     = $input['data'];
        $entity->position = $input['position'];
    }
    
}