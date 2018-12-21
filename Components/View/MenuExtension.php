<?php

namespace ProVallo\Plugins\Frontend\Components\View;

use ProVallo\Core;

class MenuExtension extends \Twig_Extension
{
    
    public function getName()
    {
        return 'Menu ViewExtension';
    }
    
    public function getFunctions()
    {
        $functions = ['getMenu'];
        $result    = [];
        
        foreach($functions as $functionName)
        {
            $result[] = new \Twig_SimpleFunction($functionName, [$this, $functionName . 'Function']);
        }
        
        return $result;
    }
    
    public function getMenuFunction ()
    {
        /** @var \ProVallo\Plugins\Frontend\Components\Menu $menuService */
        $menuService = Core::di()->get('frontend.menu');
        
        return $menuService->generate();
    }
    
}