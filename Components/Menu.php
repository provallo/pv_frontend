<?php

namespace ProVallo\Plugins\Frontend\Components;

use ProVallo\Plugins\Frontend\Models\Page\Page;

class Menu
{
    
    public function generate ()
    {
        return $this->buildMenu(-1);
    }
    
    protected function buildMenu ($parentID)
    {
        $items  = Page::repository()->findBy([
            'parentID' => $parentID,
            'active'   => 1
        ]);
        
        $result = [];
        
        foreach ($items as $item)
        {
            $result[] = [
                'id'       => $item->id,
                'label'    => $item->label,
                'route'    => ltrim($item->route, '/'),
                'position' => $item->position,
                'items'    => $this->buildMenu($item->id)
            ];
        }
        
        return $result;
    }
    
}