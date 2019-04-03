<?php

namespace ProVallo\Plugins\Frontend\Components;

use Favez\Mvc\DI\Injectable;
use Psr\Http\Message\UriInterface;

class Domain
{
    use Injectable;
    
    /**
     * @var \ProVallo\Plugins\Frontend\Models\Domain\Domain
     */
    protected $domain;
    
    /**
     * @var bool
     */
    protected $replacedID = false;
    
    public function __construct ()
    {
        $this->domain  = null;
    }
    
    /**
     * @return \ProVallo\Plugins\Frontend\Models\Domain\Domain|null
     */
    public function getCurrentDomain ()
    {
        return $this->domain;
    }
    
    /**
     * In case you need to override the ID for whatever reason
     *
     * @param integer $id
     */
    public function overrideID ($id)
    {
        $this->domain->id = (int) $id;
        $this->replacedID = true;
    }
    
    public function replacedID ()
    {
        return $this->replacedID;
    }
    
    public function getThemeID ()
    {
        if ($this->replacedID())
        {
            $themeID = (int) self::db()->from('domain')
                ->where('id = ?', $this->domain->id)
                ->select(null)
                ->select('themeID')
                ->fetch();
            
            return $themeID;
        }
        
        return $this->getCurrentDomain()->themeID;
    }
    
    public function getAlternativeID ()
    {
        $uri    = self::request()->getUri();
        $host   = $uri->getHost();
        $id     = (int) self::db()->from('domain')->where('host = ?', $host)->fetchColumn();
        
        return $id;
    }
    
    /**
     * Lookup for the correct domain, redirect if required or die when no valid
     * domain is matched
     *
     * @throws \Exception
     */
    public function checkRedirect ()
    {
        if ($this->domain !== null)
        {
            return;
        }
        
        $uri    = self::request()->getUri();
        $host   = $uri->getHost();
        $domain = \ProVallo\Plugins\Frontend\Models\Domain\Domain::repository()->findOneBy([
            'host' => $host,
            'active' => 1
        ]);
        
        if ($domain instanceof \ProVallo\Plugins\Frontend\Models\Domain\Domain)
        {
            if (!$this->validateScheme($domain, $uri))
            {
                $this->redirectTo($uri);
            }
            else
            {
                $this->domain = $domain;
                
                self::events()->publish('frontend.domain.selected', [
                    'domain' => $domain
                ]);
            }
        }
        else
        {
            $id = (int) self::db()->from('domain')->where('hosts LIKE ?', '%' . $host . '%')->where('active = 1')->fetchColumn();
            
            if ($id > 0)
            {
                /** @var \ProVallo\Plugins\Frontend\Models\Domain\Domain $domain */
                $domain = \ProVallo\Plugins\Frontend\Models\Domain\Domain::repository()->find($id);
                $uri    = $uri->withHost($domain->host);
                
                $this->validateScheme($domain, $uri);
                $this->redirectTo($uri);
            }
            else
            {
                self::events()->publish('frontend.domain.not_found', [
                    'uri' => $uri
                ]);
                
                die('No site configured at this address.');
            }
        }
    }
    
    /**
     * Validates and fixes the scheme.
     *
     * @param \ProVallo\Plugins\Frontend\Models\Domain\Domain $domain
     * @param \Psr\Http\Message\UriInterface                  $uri
     *
     * @return bool
     */
    protected function validateScheme ($domain, UriInterface &$uri)
    {
        $secure = $uri->getScheme() === 'https';
        
        if ($domain->secure && !$secure)
        {
            $uri = $uri->withScheme('https');
            
            return false;
        }
        else
        {
            if (!$domain->secure && $secure)
            {
                $uri = $uri->withScheme('http');
                
                return false;
            }
        }
        
        return true;
    }
    
    protected function redirectTo (UriInterface $uri)
    {
        self::events()->publish('frontend.domain.redirect', [
            'uri' => $uri
        ]);
        
        header('Location: ' . $uri, true, 301);
        die;
    }
    
}