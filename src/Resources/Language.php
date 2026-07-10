<?php

namespace hstanleycrow\EasyPHPDatatables\Resources;

class Language
{
    private const FILE_MAP = [
        'es' => 'es-ES',
        'es-MX' => 'es-MX',
    ];

    public static function inlineConfig(string $language): string
    {
        if ($language === 'en') {
            return '';
        }
        if (!isset(self::FILE_MAP[$language])) {
            throw new \Exception('Invalid language specified');
        }

        $file = __DIR__ . '/i18n/' . self::FILE_MAP[$language] . '.json';
        $json = @file_get_contents($file);
        if ($json === false) {
            return '';
        }

        return 'language: ' . trim($json) . ',';
    }
}
