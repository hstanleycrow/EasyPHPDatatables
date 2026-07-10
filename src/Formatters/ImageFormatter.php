<?php

namespace hstanleycrow\EasyPHPDatatables\Formatters;

use hstanleycrow\EasyPHPDatatables\Config;

class ImageFormatter implements IColumnFormatterGenerator
{
    public function generate(): callable
    {
        $classes = Config::instance()->getImageClasses();
        return function ($d, $row) use ($classes) {
            return '<img src="' . $d . '" class="' . $classes . '" width="35" />';
        };
    }
}
