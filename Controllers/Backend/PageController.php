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
    
    protected function getListQuery ()
    {
        $domainID = (int) self::request()->getParam('domainID');
        
        return parent::getListQuery()
            ->where('domainID = ? OR domainID = 0', $domainID);
    }
    
    protected function setDefaultValues (Entity $entity)
    {
        $entity->created  = date('Y-m-d H:i:s');
        $entity->parentID = -1;
    }
    
    protected function setValues (Entity $entity, $input)
    {
        $entity->changed = date('Y-m-d H:i:s');
        
        $entity->parentID = -1;
        $entity->domainID = (int) $input['domainID'];
        $entity->active   = (int) $input['active'];
        $entity->hideTop  = (int) $input['hideTop'];
        $entity->route    = $input['route'];
        $entity->label    = $input['label'];
        $entity->title    = $input['title'];
        $entity->type     = $input['type'];
        $entity->data     = $input['data'];
        $entity->position = $input['position'];
    }
    
    public function previewAction ()
    {
        try
        {
            $html = self::forward('preview', 'Front', 'frontend');
        }
        catch (\Exception $ex)
        {
            return $ex->getMessage();
        }
        
        return $html;
    }
    
}