<?php

namespace ProVallo\Plugins\Frontend;

use ProVallo\Core;

class Bootstrap extends \ProVallo\Components\Plugin\Bootstrap
{
    
    public function install ()
    {
        $this->createThemeDirectory();
        $this->registerCustomTheme(path($this->getPath(), 'Views'));
    }
    
    public function execute()
    {
        Core::instance()->registerModule('frontend', [
            'controller' => [
                'namespace'     => 'ProVallo\\Controllers\\Frontend\\',
                'class_suffix'  => 'Controller',
                'method_suffix' => 'Action'
            ]
        ]);
    
        // Register routes
        Core::instance()->get('/', 'frontend:Index:index');
    
        // Register all frontend controllers
        $this->registerController('Frontend', 'Index');
    }
    
    protected function registerCustomTheme($path, $name = 'default')
    {
        $directory = path(Core::path(), Core::instance()->config('view.theme_path'), 'frontend', $name);
        
        if (!is_link($directory))
        {
            var_dump($path, $directory);
            
            return link($path, $directory);
        }
        
        return true;
    }
    
    protected function createThemeDirectory ()
    {
        // Create theme directory if not exists
        $directory = path(Core::path(), Core::instance()->config('view.theme_path'), 'frontend');
        
        if (!is_dir($directory))
        {
            mkdir($directory, 0777, true);
        }
    }

}