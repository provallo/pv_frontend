<?php

namespace ProVallo\Plugins\Frontend\Components\Translation;

interface TranslatedEntityInterface
{
    
    /**
     * Defines the translation for the entity.
     *
     * The returned array needs the following keys:
     * - string translationEntity
     * - string translationForeignKey
     * - array  fields
     *
     * @return array
     */
    public static function getTranslationConfig(): array;
    
}