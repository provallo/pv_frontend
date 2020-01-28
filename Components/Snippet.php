<?php

namespace ProVallo\Plugins\Frontend\Components;

use ProVallo\Core;

class Snippet
{
    
    /**
     * @var \ProVallo\Plugins\Frontend\Components\Translation\Translation
     */
    protected $translation;
    
    /**
     * @var \ProVallo\Plugins\Frontend\Components\Domain
     */
    protected $domain;
    
    /**
     * @var integer
     */
    protected $languageID;
    
    /**
     * @var integer
     */
    protected $domainID;
    
    /**
     * @var array
     */
    protected $snippets;
    
    public function __construct ()
    {
        $this->translation = Core::di()->get('frontend.translation');
        $this->domain      = Core::di()->get('frontend.domain');
        $this->languageID  = $this->translation->getLanguage();
        $this->domainID    = $this->domain->getCurrentDomain()->id;
        $this->snippets    = [];
        
        $this->loadSnippets();
    }
    
    /**
     * Fetches the value of the according snippet.
     *
     * If no snippet found, the snippet will be created immediately.
     *
     * @param string $key
     * @param string $defaultValue
     * @param array  $params
     *
     * @return string
     */
    public function get (string $key, string $defaultValue = '', array $params = []): string
    {
        if (!isset($this->snippets[$key]))
        {
            $snippet         = \ProVallo\Plugins\Frontend\Models\Snippet\Snippet::create();
            $snippet->name   = $key;
            $snippet->values = [
                [
                    'domainID'   => $this->domainID,
                    'languageID' => $this->languageID,
                    'value'      => $defaultValue
                ]
            ];
    
            $snippet->save();
    
            $this->snippets[$key] = $defaultValue;
        }
        
        return sprintf($this->snippets[$key], ...$params);
    }
    
    /**
     * Loads all snippets for the current language into cache.
     */
    protected function loadSnippets (): void
    {
        $values = Core::db()->from('snippet s')
            ->select(null)->select('s.id, s.name, v.value')
            ->leftJoin('snippet_value v ON v.snippetID = s.id')
            ->where('v.domainID = ?', $this->domainID)
            ->where('v.languageID = ?', $this->languageID)
            ->execute()
            ->fetchAll(\PDO::FETCH_ASSOC);
        
        foreach ($values as $value)
        {
            $this->snippets[$value['name']] = $value['value'];
        }
    }
    
}