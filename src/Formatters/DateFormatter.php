<?php

namespace hstanleycrow\EasyPHPDatatables\Formatters;

class DateFormatter implements IColumnFormatterGenerator
{
    public function generate()
    {
        return function ($data, $row) {
            $dateFormat = $_ENV['DT_DATE_FORMAT'] ?? 'd/m/Y';
            return date($dateFormat, strtotime($data));
        };
    }
}
