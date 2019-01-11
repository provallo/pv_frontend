<?php

namespace ProVallo\Plugins\Frontend\Components\Themes;

use Favez\Mvc\DI\Injectable;
use ProVallo\Plugins\Frontend\Models\Theme\Theme;

class Themes
{
    use Injectable;
    
    /**
     * @var \ProVallo\Plugins\Frontend\Components\Themes\Compiler
     */
    protected $compiler;
    
    public function ensureDirectory ()
    {
        $directory = $this->getThemeDirectory();
        
        if (!is_dir($directory))
        {
            mkdir($directory, 0777, true);
        }
    }
    
    /**
     * Method to register a custom frontend theme.
     *
     * @param string $name The technical name of the theme (for the symlink)
     * @param string $path The destination of the actual theme
     *
     * @return bool
     */
    public function register ($name, $path)
    {
        $directory = path($this->getThemeDirectory(), $name);
        
        if (!is_link($directory))
        {
            return symlink($path, $directory);
        }
        
        return true;
    }
    
    /**
     * Synchronizes themes with database.
     */
    public function synchronize ()
    {
        $directory = $this->getThemeDirectory();
        $iterator  = new \IteratorIterator(new \DirectoryIterator($directory));
        
        /** @var \Favez\ORM\Entity\ArrayCollection $themes */
        $themes = Theme::repository()->findAll();
        $found  = [];
        
        foreach ($iterator as $file)
        {
            if ($file->isDot() || $file->isFile())
            {
                continue;
            }
            
            $themeName = $file->getFilename();
            
            foreach ($themes as $i => $theme)
            {
                if ($theme->name === $themeName)
                {
                    $found[] = $theme->id;
                    continue 2;
                }
            }
            
            $theme = Theme::create();
            $theme->name = $themeName;
            $theme->created = date('Y-m-d H:i:s');
            $theme->save();
        }
        
        foreach ($themes as $theme)
        {
            if (!in_array($theme->id, $found))
            {
                $theme->remove();
            }
        }
    }
    
    public function getThemeDirectory ()
    {
        return path(self::path(), self::config('view.theme_path'), 'frontend');
    }
    
    /**
     * @return \ProVallo\Plugins\Frontend\Components\Themes\Compiler
     */
    public function getCompiler ()
    {
        if (!($this->compiler instanceof Compiler))
        {
            $this->compiler = new Compiler($this);
        }
        
        return $this->compiler;
    }
    
}