<?php

namespace ProVallo\Controllers\Backend;

use Favez\ORM\Entity\Entity;
use ProVallo\Plugins\Backend\Components\Controllers\API;
use ProVallo\Plugins\Frontend\Models\Domain\Domain;
use ProVallo\Plugins\Frontend\Models\Page\Page;
use ProVallo\Plugins\Frontend\Models\Page\PageTranslation;

class PageController extends API
{
    
    public function configure ()
    {
        return [
            'model' => Page::class,
            'detail' => [
                'recursive' => true
            ]
        ];
    }
    
    protected function map ($row)
    {
        $row['id']           = (int) $row['id'];
        $row['translations'] = [];
        
        $page = Page::repository()->find($row['id']);
        
        if ($page instanceof Page)
        {
            // Load available translations
            $row['translations'] = $page->translations->toArray();
            
            // Add pseudo translation for every available language
            $domainID = (int) self::request()->getParam('domainID');
            
            if ($domainID > 0)
            {
                $domain = Domain::repository()->find($domainID);
                
                foreach ($domain->languages as $language)
                {
                    foreach ($row['translations'] as $translation)
                    {
                        if ($translation['languageID'] === $language->id)
                        {
                            continue 2;
                        }
                    }
                    
                    $translation             = PageTranslation::create();
                    $translation->pageID     = $row['id'];
                    $translation->languageID = $language->id;
                    $translation->label      = $page->label;
                    $translation->title      = $page->title;
                    $translation->data       = $page->data;
                    
                    $row['translations'][] = $translation->toArray();
                }
            }
            
            foreach ($row['translations'] as &$translation)
            {
                $translation['id']         = (int) $translation['id'];
                $translation['languageID'] = (int) $translation['languageID'];
                $translation['pageID']     = (int) $translation['pageID'];
            }
        }
        
        return $row;
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
        
        $entity->parentID     = -1;
        $entity->domainID     = (int) $input['domainID'];
        $entity->active       = (int) $input['active'];
        $entity->hideTop      = (int) $input['hideTop'];
        $entity->route        = $input['route'];
        $entity->label        = $input['label'];
        $entity->title        = $input['title'];
        $entity->type         = $input['type'];
        $entity->data         = $input['data'];
        $entity->position     = $input['position'];
        $entity->translations = $input['translations'];
    }
    
    public function previewAction ()
    {
        try
        {
            $html = $this->forward('preview', 'Front', 'frontend');
        }
        catch (\Exception $ex)
        {
            return $ex->getMessage();
        }
        
        return $html;
    }
    
}