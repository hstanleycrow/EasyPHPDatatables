<?php

namespace hstanleycrow\EasyPHPDatatables\Formatters;

class TimeFormatter implements IColumnFormatterGenerator
{
    public function generate(): callable
    {
        return function ($d, $row) {
            return date('H:i:s', strtotime($d));
        };
    }
}
