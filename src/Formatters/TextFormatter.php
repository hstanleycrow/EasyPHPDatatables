<?php

namespace hstanleycrow\EasyPHPDatatables\Formatters;

class TextFormatter implements IColumnFormatterGenerator
{
    public function generate(): callable
    {
        return function ($data, $row) {
            return $data;
        };
    }
}
