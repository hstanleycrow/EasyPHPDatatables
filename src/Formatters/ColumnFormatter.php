<?php

namespace hstanleycrow\EasyPHPDatatables\Formatters;

class ColumnFormatter
{
    public function generate(IColumnFormatterGenerator $formatter)
    {
        $formatter->generate();
    }
}
