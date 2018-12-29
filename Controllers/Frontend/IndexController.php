<?php

namespace ProVallo\Controllers\Frontend;

use ProVallo\Components\Controller;
use ProVallo\Core;
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
            'page'      => $page,
            'timestamp' => $this->getTimestamp()
        ]);
    }
    
    public function openLinkAction ()
    {
        $url = self::request()->getParam('url');
        
        return self::response()->withRedirect($url);
    }
    
    public function previewAction ()
    {
        $data = self::request()->getParams();
        $page = [
            'id'    => $data['id'],
            'title' => $data['label'],
            'html'  => $data['data']
        ];
        
        $parser = new \Parsedown();
        $parser->setSafeMode(true);
        $page['html'] = $parser->parse($page['html']);
        
        Core::di()->get('frontend.domain')->overrideID($data['domainID']);
        
        return self::view()->render('frontend/index/index', [
            'page' => $page
        ]);
    }
    
    protected function getTimestamp ()
    {
        $filename  = path(self::path(), 'ext/Frontend/Views/_resources/timestamp.txt');
        $timestamp = time();
        
        if (is_file($filename))
        {
            $timestamp = (int) file_get_contents($filename);
        }
        else
        {
            file_put_contents($filename, $timestamp);
        }
        
        return $timestamp;
    }
    
    protected function getPage ()
    {
        $path = self::request()->getUri()->getPath();
        $page = Page::repository()->findOneBy([
            'route'    => $path,
            'active'   => 1,
            'domainID' => Core::di()->get('frontend.domain')->getCurrentDomain()->id
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