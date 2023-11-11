<?php

namespace hstanleycrow\EasyPHPDatatables\Formatters;

class DecimalFormatter implements IColumnFormatterGenerator
{
    public function generate()
    {
        return function ($data, $row) {
            $decimalSeparator = NumberFormatConfig::getDecimalSeparator();
            $thousandsSeparator = NumberFormatConfig::getThousandsSeparator();
            $decimals = NumberFormatConfig::getDecimals();

            return number_format(
                $data,
                $decimals,
                $thousandsSeparator,
                $decimalSeparator
            );
        };
    }
}
