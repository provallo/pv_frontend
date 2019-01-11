<?php

namespace ProVallo\Plugins\Frontend\Components\Themes;

use Favez\Mvc\DI\Injectable;
use ProVallo\Plugins\Frontend\Models\Domain\Domain;
use ProVallo\Plugins\Frontend\Models\Theme\Theme;

class Compiler
{
    use Injectable;
    
    /**
     * @var Themes
     */
    protected $themeService;
    
    public function __construct (Themes $themeService)
    {
        $this->themeService = $themeService;
    }
    
    public function compile (Theme $theme, Domain $domain)
    {
        $themeDir     = path($this->themeService->getThemeDirectory(), $theme->name);
        $resourceDir  = path($themeDir, '_resources');
        $yamlFilename = path($themeDir, 'theme.yaml');
        
        if (is_file($yamlFilename) === false)
        {
            throw new \Exception('Missing theme.yaml in "' . $themeDir . '"');
        }
        
        $config = \Symfony\Component\Yaml\Yaml::parseFile($yamlFilename);
        $config = array_replace_recursive([
            'name'       => '',
            'less'       => [],
            'javascript' => []
        ], $config);
        
        if (is_dir($themeDir))
        {
            $less       = $this->collectLess($config);
            $javascript = $this->collectJavascript($config);
            
            foreach ($less as &$filename)
            {
                if ($filename[0] !== '/')
                {
                    $filename = path($resourceDir, 'less', $filename);
                }
            }
    
            foreach ($javascript as &$filename)
            {
                if ($filename[0] !== '/')
                {
                    $filename = path($resourceDir, 'js', $filename);
                }
            }
            
            $css        = $this->buildCss($less);
            $javascript = $this->buildJavascript($javascript);
            
            $cssFilename  = path($resourceDir, 'dist', sprintf('all_%d.css', $domain->id));
            $jsFilename   = path($resourceDir, 'dist', sprintf('all_%d.js', $domain->id));
            $timeFilename = path($resourceDir, 'dist/timestamp.txt');
            
            $this->writeFile($cssFilename, $css);
            $this->writeFile($jsFilename, $javascript);
            $this->writeFile($timeFilename, time());
        }
    }
    
    /**
     * Fetch and concat Javascript files into a single file
     *
     * @param array $files
     *
     * @return string
     * @throws \Exception
     */
    protected function buildJavascript ($files)
    {
        $javascript = '';
        
        foreach ($files as $filename)
        {
            if (!is_file($filename))
            {
                throw new \Exception('Missing javascript file: ' . $filename);
            }
            
            $javascript .= file_get_contents($filename);
        }
        
        return $javascript;
    }
    
    /**
     * Builds and concat LESS files into single CSS file
     *
     * @param array $files
     *
     * @return string
     * @throws \Exception
     */
    protected function buildCss ($files)
    {
        $css = '';
        
        foreach ($files as $filename)
        {
            if (!is_file($filename))
            {
                throw new \Exception('Missing less file: ' . $filename);
            }
            
            $css .= `lessc $filename`;
        }
        
        return $css;
    }
    
    /**
     * Collects less files
     *
     * @param array $config
     *
     * @return array
     */
    protected function collectLess (array $config)
    {
        $files = $config['less'];
        
        foreach (self::events()->collect('frontend.register.less') as $filename)
        {
            $files[] = $filename;
        }
        
        return $files;
    }
    
    /**
     * Collects javascript files
     *
     * @param array $config
     *
     * @return array
     */
    protected function collectJavascript (array $config)
    {
        $files = $config['javascript'];
        
        foreach (self::events()->collect('frontend.register.javascript') as $filename)
        {
            $files[] = $filename;
        }
        
        return $files;
    }
    
    protected function writeFile ($filename, $content)
    {
        $this->ensureDirectory($filename);
        
        file_put_contents($filename, $content);
    }
    
    protected function ensureDirectory ($filename)
    {
        $directory = pathinfo($filename, PATHINFO_DIRNAME);
        
        if (!file_exists($directory))
        {
            mkdir($directory, 0777, true);
        }
    }
    
}