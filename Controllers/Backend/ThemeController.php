<?php

namespace ProVallo\Controllers\Backend;

use ProVallo\Core;
use ProVallo\Plugins\Backend\Components\Controllers\API;
use ProVallo\Plugins\Frontend\Models\Theme\Theme;

class ThemeController extends API
{
    
    public function configure ()
    {
        return [
            'model' => Theme::class
        ];
    }
    
    protected function getListQuery ()
    {
        $themes = Core::di()->get('frontend.themes');
        $themes->synchronize();
        
        return parent::getListQuery();
    }
    
}