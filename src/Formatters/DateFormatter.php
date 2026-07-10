<?php

namespace hstanleycrow\EasyPHPDatatables\Formatters;

use hstanleycrow\EasyPHPDatatables\Config;

class DateFormatter implements IColumnFormatterGenerator
{
    public function generate(): callable
    {
        $dateFormat = Config::instance()->getDateFormat();
        return function ($data, $row) use ($dateFormat) {
            return date($dateFormat, strtotime($data));
        };
    }
}
