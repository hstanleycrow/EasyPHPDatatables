<?php

namespace hstanleycrow\EasyPHPDatatables\Formatters;

use hstanleycrow\EasyPHPDatatables\Config;

class MoneyFormatter implements IColumnFormatterGenerator
{
    public function generate(): callable
    {
        $config = Config::instance();
        return function ($data, $row) use ($config) {
            return $config->getMoneySymbol() . number_format(
                (float) $data,
                $config->getDecimals(),
                $config->getDecimalSeparator(),
                $config->getThousandSeparator()
            );
        };
    }
}
