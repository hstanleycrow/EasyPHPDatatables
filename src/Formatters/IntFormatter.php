<?php

namespace hstanleycrow\EasyPHPDatatables\Formatters;

class IntFormatter implements IColumnFormatterGenerator
{
    public function generate()
    {
        return function ($data, $row) {
            return (int) $data;
        };
    }
}
