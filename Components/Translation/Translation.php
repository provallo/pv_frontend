<?php

namespace ProVallo\Plugins\Frontend\Components\Translation;

use ProVallo\Core;

class Translation
{
    
    /**
     * @var \ProVallo\Plugins\Frontend\Models\Domain\Domain
     */
    protected $domain;
    
    /**
     * @var \SlimSession\Helper
     */
    protected $session;
    
    public function __construct ()
    {
        $this->domain  = Core::di()->get('frontend.domain')->getCurrentDomain();
        $this->session = Core::session();
    }
    
    /**
     * Translates a single entity by selected language
     *
     * @param TranslatedEntityInterface $entity
     *
     * @return TranslatedEntityInterface
     * @throws \Exception
     */
    public function translateEntity (TranslatedEntityInterface $entity): TranslatedEntityInterface
    {
        $config      = $entity->getTranslationConfig();
        $repository  = Core::models()->getRepository($config['translationEntity']);
        $translation = $repository->findOneBy([
            $config['translationForeignKey'] => $entity->id,
            'languageID'                     => $this->getLanguage()
        ]);
        
        if ($translation instanceof $config['translationEntity'])
        {
            foreach ($config['fields'] as $field)
            {
                if (empty($translation->{$field}))
                {
                    continue;
                }
                
                $entity->{$field} = $translation->{$field};
            }
        }
        
        return $entity;
    }
    
    /**
     * Returns available languages for current domain
     *
     * @return array
     */
    public function getLanguages (): array
    {
        $languages = $this->domain->languages;
        
        return array_map(
            function ($language)
            {
                $language['selected'] = (int) $language['id'] === $this->getLanguage();
                
                return $language;
            },
            $languages->toArray()
        );
    }
    
    /**
     * Get selected language
     *
     * If no selection exist, return domains default language
     *
     * @return int
     */
    public function getLanguage (): int
    {
        $languageID = (int) $this->session->get('frontend_language', null);
        
        if ($languageID === 0)
        {
            $languageID = (int) $this->domain->languageID;
        }
        
        return $languageID;
    }
    
    /**
     * Save languageID into session
     *
     * @param int $languageID
     */
    public function setLanguage (int $languageID): void
    {
        $this->session->set('frontend_language', $languageID);
    }
    
}