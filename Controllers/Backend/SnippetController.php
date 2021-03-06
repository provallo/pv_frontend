<?php

namespace ProVallo\Controllers\Backend;

use Favez\ORM\Entity\Entity;
use ProVallo\Plugins\Backend\Components\Controllers\API;
use ProVallo\Plugins\Frontend\Models\Domain\Domain;
use ProVallo\Plugins\Frontend\Models\Snippet\Snippet;
use ProVallo\Plugins\Frontend\Models\Snippet\Value;

class SnippetController extends API
{
    
    public function configure ()
    {
        return [
            'model'  => Snippet::class,
            'detail' => [
                'recursive' => true
            ]
        ];
    }
    
    protected function map ($row)
    {
        $row['id']     = (int) $row['id'];
        $row['values'] = [];
        
        $snippet = Snippet::repository()->find($row['id']);
        
        if ($snippet instanceof Snippet)
        {
            // Load available values
            $row['values'] = $snippet->values->toArray();
            
            $domains = Domain::repository()->findAll();
            
            foreach ($domains as $domain)
            {
                $languages = $domain->languages;
                
                foreach ($languages as $language)
                {
                    foreach ($row['values'] as $value)
                    {
                        if ($value['languageID'] === $language->id
                            && $value['domainID'] === $domain->id
                        )
                        {
                            continue 2;
                        }
                    }
                    
                    $value             = Value::create();
                    $value->snippetID  = $row['id'];
                    $value->domainID   = $domain->id;
                    $value->languageID = $language->id;
                    $value->value      = '';
                    
                    $row['values'][] = $value->toArray();
                }
            }
            
            foreach ($row['values'] as &$value)
            {
                $value['id']         = (int) $value['id'];
                $value['languageID'] = (int) $value['languageID'];
                $value['domainID']   = (int) $value['domainID'];
                $value['snippetID']  = (int) $value['snippetID'];
            }
        }
        
        return $row;
    }
    
    protected function setValues (Entity $entity, $input)
    {
        $entity->name   = $input['name'];
        $entity->values = $input['values'];
    }
    
}