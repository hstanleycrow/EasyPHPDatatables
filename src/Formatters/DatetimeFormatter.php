<?php

namespace hstanleycrow\EasyPHPDatatables\Formatters;

use hstanleycrow\EasyPHPDatatables\Config;

class DatetimeFormatter implements IColumnFormatterGenerator
{
    public function generate(): callable
    {
        $format = Config::instance()->getDatetimeFormat();
        return function ($data, $row) use ($format) {
            return date($format, strtotime($data));
        };
    }
}
