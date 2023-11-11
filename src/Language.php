<?php

namespace hstanleycrow\EasyPHPDatatables;

class Language
{
    protected static string $language = 'en';
    protected static string $languageURL = '';

    public static function setLanguage(string $language = 'en'): static
    {
        self::$language = $language;
        switch ($language) {
            case 'en':
                self::$languageURL = '';
                break;
            case 'es':
                self::$languageURL = '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json';
                break;
            case 'es-MX':
                self::$languageURL = '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-MX.json';
                break;
            default:
                throw new \Exception('Invalid language specified');
        }
        return new static;
    }
    public static function autoLoadLanguageURL(): string
    {
        if (self::$language != 'en')
            return "language: {
            url: '" . self::$languageURL . "'
        },";
        return "";
    }
}
