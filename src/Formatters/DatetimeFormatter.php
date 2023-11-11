<?php

namespace hstanleycrow\EasyPHPDatatables\Formatters;

class DatetimeFormatter implements IColumnFormatterGenerator
{
    public function generate()
    {
        return function ($data, $row) {
            $format = $_ENV['DT_DATETIME_FORMAT'] ?? 'd/m/Y H:i';
            return date($format, strtotime($data));
        };
    }
}
