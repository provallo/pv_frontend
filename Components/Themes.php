<?php

namespace ProVallo\Plugins\Frontend\Components;

use ProVallo\Core;

class Themes
{
    
    public function ensureDirectory ()
    {
        $directory = path(Core::path(), Core::instance()->config('view.theme_path'), 'frontend');
    
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
        $directory = path(Core::path(), Core::instance()->config('view.theme_path'), 'frontend', $name);
    
        if (!is_link($directory))
        {
            return symlink($path, $directory);
        }
    
        return true;
    }
    
}