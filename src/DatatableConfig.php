<?php

namespace hstanleycrow\EasyPHPDatatables;


class DatatableConfig
{
    public static function getDefaultLanguage(): string
    {
        return $_ENV['DT_LANGUAGE'] ?? 'en';
    }

    public static function getDefaultRowsPerPage(): int
    {
        return $_ENV['DT_PAGE_LENGTH'] ?? 25;
    }

    public static function getDefaultCssClasses(): string
    {
        return $_ENV['DT_TABLE_CLASSES'] ?? 'table';
    }

    public static function getDefaultErrorMessage(): string
    {
        return $_ENV['DT_ERROR_MESSAGE'] ?? 'Error loading table data. Please update. If the error persists contact the developer.';
    }
}
