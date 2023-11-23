<?php

namespace hstanleycrow\EasyPHPDatatables\Formatters;

class DateFormatter implements IColumnFormatterGenerator
{
    public function generate(): callable
    {
        $dateFormat = $_ENV['DT_DATE_FORMAT'] ?? 'd/m/Y';
        return function ($data, $row) use ($dateFormat) {
            return date($dateFormat, strtotime($data));
        };
    }
}
