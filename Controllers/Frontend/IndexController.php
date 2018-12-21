<?php

namespace ProVallo\Controllers\Frontend;

use ProVallo\Components\Controller;

class IndexController extends Controller
{
    
    public function indexAction ()
    {
        
        
        return self::view()->render('frontend/index/index');
    }
    
}