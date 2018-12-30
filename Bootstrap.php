<?php

namespace ProVallo\Plugins\Frontend;

use Favez\Mvc\Event\Arguments;
use Favez\Mvc\View\View;
use ProVallo\Core;
use ProVallo\Plugins\Frontend\Commands\FrontendBuildCommand;
use ProVallo\Plugins\Frontend\Components\Domain;
use ProVallo\Plugins\Frontend\Components\Menu;
use ProVallo\Plugins\Frontend\Components\View\MenuExtension;
use Slim\Http\Request;
use Slim\Http\Response;

class Bootstrap extends \ProVallo\Components\Plugin\Bootstrap
{
    
    public function install ()
    {
        $this->installDB();
        $this->createThemeDirectory();
        $this->registerCustomTheme(path($this->getPath(), 'Views'));
    }
    
    public function update ($previousVersion)
    {
        $this->installDB();
        
        return true;
    }
    
    public function execute()
    {
        // Register composer dependencies
        require_once __DIR__ . '/vendor/autoload.php';
        
        if (Core::instance()->getApi() === Core::API_WEB)
        {
            Core::events()->subscribe('core.route.register', function () {
                Core::instance()->registerModule('frontend', [
                    'controller' => [
                        'namespace'     => 'ProVallo\\Controllers\\Frontend\\',
                        'class_suffix'  => 'Controller',
                        'method_suffix' => 'Action'
                    ]
                ]);
    
                // Override notFoundHandler
                Core::instance()->getContainer()['notFoundHandler'] = function() {
                    return function (Request $request, Response $response) {
                        $result = Core::instance()->dispatcher()->dispatch('frontend:Front:index', []);
            
                        if (!($result instanceof Response))
                        {
                            $response->getBody()->write($result);
                
                            return $response;
                        }
            
                        return $result;
                    };
                };
    
                // Register custom controllers
                $this->registerController('Frontend', 'Front');
                $this->registerController('Backend', 'Page');
                $this->registerController('Backend', 'Domain');
            });
    
            // Register custom services
            Core::di()->registerShared('frontend.menu', function() {
                return new Menu();
            });
            
            Core::di()->registerShared('frontend.domain', function () {
                return new Domain();
            });
    
            // Register view extensions
            Core::events()->subscribe('core.view.init', function (Arguments $args) {
                /** @var View $view */
                $view = $args->get(0);
                $view->engine()->addExtension(new MenuExtension());
                $view->engine()->addExtension(new \Twig_Extension_StringLoader());
            });
            
            Core::events()->subscribe('controller.pre_dispatch.frontend', function () {
                Core::di()->get('frontend.domain')->checkRedirect();
            });
        }
        
        if (Core::instance()->getApi() === Core::API_CONSOLE)
        {
            // Register backend extensions
            Core::events()->subscribe('backend.register', function () {
                return [
                    $this
                ];
            });
    
            // Register frontend resources
            Core::events()->subscribe('frontend.register.less', function () {
                return [
                    path($this->getPath(), 'Views/_resources/less/all.less')
                ];
            });
    
            // Register custom console commands
            Core::events()->subscribe('console.register', function () {
                return [
                    new FrontendBuildCommand()
                ];
            });
        }
    }
    
    protected function registerCustomTheme($path, $name = 'default')
    {
        $directory = path(Core::path(), Core::instance()->config('view.theme_path'), 'frontend', $name);
        
        if (!is_link($directory))
        {
            return symlink($path, $directory);
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