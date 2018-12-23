<?php

namespace ProVallo\Controllers\Frontend;

use ProVallo\Components\Controller;
use ProVallo\Plugins\Frontend\Models\Page\Page;

class IndexController extends Controller
{
    
    public function indexAction ()
    {
        $page = $this->getPage();
        
        if (empty($page))
        {
            return self::view()->render('frontend/index/404');
        }
        
        return self::view()->render('frontend/index/index', [
            'page' => $page
        ]);
    }
    
    public function openLinkAction ()
    {
        $url = self::request()->getParam('url');
        
        return self::response()->withRedirect($url);
    }
    
    protected function getPage ()
    {
        $path = self::request()->getUri()->getPath();
        $page = Page::repository()->findOneBy([
            'route'  => $path,
            'active' => 1
        ]);
        
        if ($page instanceof Page)
        {
            $parser = new \Parsedown();
            $parser->setSafeMode(true);
            $html   = $parser->parse($page->data);
            
            return [
                'id'    => $page->id,
                'title' => $page->label,
                'html'  => $html
            ];
        }
        
        return null;
    }
    
}