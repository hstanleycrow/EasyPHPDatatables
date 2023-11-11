<?php

namespace hstanleycrow\EasyPHPDatatables\Formatters;

class NumberFormatConfig
{
    public static function getDecimalSeparator()
    {
        return $_ENV['DT_DECIMAL_SEPARATOR'] ?? '.';
    }

    public static function getThousandsSeparator()
    {
        return $_ENV['DT_THOUSAND_SEPARATOR'] ?? ',';
    }

    public static function getDecimals()
    {
        return $_ENV['DT_DECIMALS'] ?? 2;
    }
}
