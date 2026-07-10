<?php

namespace hstanleycrow\EasyPHPDatatables\Formatters;

use hstanleycrow\EasyPHPDatatables\Config;

class DecimalFormatter implements IColumnFormatterGenerator
{
    public function generate(): callable
    {
        $config = Config::instance();
        return function ($data, $row) use ($config) {
            return number_format(
                (float) $data,
                $config->getDecimals(),
                $config->getDecimalSeparator(),
                $config->getThousandSeparator()
            );
        };
    }
}
