<?php

namespace hstanleycrow\EasyPHPDatatables\Formatters;

use hstanleycrow\EasyPHPDatatables\Config;

class PercentageFormatter implements IColumnFormatterGenerator
{
    public function generate(): callable
    {
        $config = Config::instance();
        return function ($data, $row) use ($config) {
            $formattedData = number_format(
                (float) $data,
                $config->getDecimals(),
                $config->getDecimalSeparator(),
                $config->getThousandSeparator()
            );

            return $config->getPercentagePosition() === 'left'
                ? '%' . $formattedData
                : $formattedData . '%';
        };
    }
}
