<?php

namespace ProVallo\Controllers\Frontend;

use ProVallo\Components\Controller;
use ProVallo\Core;
use ProVallo\Plugins\Frontend\Bootstrap;
use ProVallo\Plugins\Frontend\Models\Page\Page;

class FrontController extends Controller
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
            'domain'    => Core::di()->get('frontend.domain')->getCurrentDomain(),
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
        $config = Bootstrap::getConfig();
        $data   = self::request()->getParams();
        $page   = [
            'id'    => $data['id'],
            'title' => $data['title'] ?: $data['label'],
            'html'  => $this->renderPage($data['data']),
            'route' => $data['route']
        ];
        
        Core::di()->get('frontend.domain')->overrideID($data['domainID']);
        Core::events()->publish('frontend.select_theme');
        
        return self::view()->render('frontend/index/index', [
            'page'   => $page,
            'domain' => [
                'id' => $data['domainID']
            ]
        ]);
    }
    
    protected function getTimestamp ()
    {
        $themeService = Core::di()->get('frontend.themes');
        $theme        = Core::di()->get('frontend.domain')->getCurrentDomain()->theme;
        $filename     = path($themeService->getThemeDirectory(), $theme->name, '_resources/dist/timestamp.txt');
        
        $themeService->getCompiler()->ensureDirectory($filename);
        
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
        
        if (!($page instanceof Page))
        {
            $page = Page::repository()->findOneBy([
                'type'     => 3,
                'active'   => 1,
                'domainID' => Core::di()->get('frontend.domain')->getCurrentDomain()->id
            ]);
        }
        
        if ($page instanceof Page)
        {
            return [
                'id'    => $page->id,
                'title' => $page->title ?: $page->label,
                'html'  => $this->renderPage($page->data),
                'route' => $page->route
            ];
        }
        
        return null;
    }
    
    protected function renderPage ($html)
    {
        $template = self::view()->engine()->createTemplate($html);
        $html     = $template->render([]);
        
        $config = Bootstrap::getConfig();
        $parser = new \Parsedown();
        $parser->setSafeMode($config['parsedown.safe_mode']);
        
        return $parser->parse($html);
    }
    
}