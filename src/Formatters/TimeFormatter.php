<?php

namespace hstanleycrow\EasyPHPDatatables\Formatters;

use hstanleycrow\EasyPHPDatatables\Config;

class TimeFormatter implements IColumnFormatterGenerator
{
    public function generate(): callable
    {
        $format = Config::instance()->getTimeFormat();
        return function ($d, $row) use ($format) {
            return date($format, strtotime($d));
        };
    }
}
