<?php

namespace ProVallo\Plugins\Frontend\Components;

use ProVallo\Core;
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
            'active'   => 1,
            'domainID' => Core::di()->get('frontend.domain')->getCurrentDomain()->id
        ], 'position ASC');
        
        $result = [];
        
        foreach ($items as $item)
        {
            $result[] = [
                'id'       => $item->id,
                'label'    => $item->label,
                'route'    => $this->getRoute($item),
                'position' => $item->position,
                'items'    => $this->buildMenu($item->id)
            ];
        }
        
        return $result;
    }
    
    protected function getRoute (Page $page)
    {
        switch ($page->type)
        {
            case Page::TYPE_CONTENT:
                return [
                    'link'   => ltrim($page->route, '/'),
                    'target' => '_self'
                ];
            break;
            case Page::TYPE_LINK_EXTERNAL:
                return [
                    'link'   => 'frontend/front/openLink?url=' . $page->route,
                    'target' => '_blank'
                ];
            break;
            default:
                return null;
        }
    }
    
}