<?php

namespace ProVallo\Plugins\Frontend;

use Favez\Mvc\Event\Arguments;
use Favez\Mvc\View\View;
use ProVallo\Core;
use ProVallo\Plugins\Frontend\Commands\FrontendBuildCommand;
use ProVallo\Plugins\Frontend\Components\Domain;
use ProVallo\Plugins\Frontend\Components\Menu;
use ProVallo\Plugins\Frontend\Components\View\MenuExtension;
use ProVallo\Plugins\Frontend\Models\Theme\Theme;
use Slim\Http\Request;
use Slim\Http\Response;

class Bootstrap extends \ProVallo\Components\Plugin\Bootstrap
{
    
    public function install ()
    {
        $this->installDB();
        $this->createConfig();
        
        $themeService = new Components\Themes\Themes();
        $themeService->ensureDirectory();
        $themeService->register('default', path($this->getPath(), 'Views'));
        
        return true;
    }
    
    public function update ($previousVersion)
    {
        $this->installDB();
        $this->createConfig();
        
        return true;
    }
    
    protected function createConfig ()
    {
        Core::di()->get('backend.config')->create($this, [
            'parsedown.safe_mode' => [
                'type' => 'checkbox',
                'label' => 'Enable safe mode for parsedown',
                'value' => true
            ]
        ]);
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
                $this->registerController('Backend', 'Theme');
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
    
            Core::events()->subscribe('controller.pre_dispatch.frontend.Front', function () {
                $domain = Core::di()->get('frontend.domain')->getCurrentDomain();
                
                if ($domain)
                {
                    $themeID = (int) $domain->themeID;
                    
                    if ($themeID > 0)
                    {
                        $theme = Theme::repository()->find($themeID);
                        
                        Core::view()->setTheme($theme->name);
                    }
                }
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
    
            // Register custom console commands
            Core::events()->subscribe('console.register', function () {
                return [
                    new FrontendBuildCommand()
                ];
            });
        }
    
        Core::di()->registerShared('frontend.themes', function () {
            return new Components\Themes\Themes();
        });
    }

    public static function getConfig ()
    {
        $plugin = Core::plugins()->get('Frontend');
        $config = Core::di()->get('backend.config')->get($plugin);
    
        return $config;
    }
    
}