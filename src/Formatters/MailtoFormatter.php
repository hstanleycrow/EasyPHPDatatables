<?php

namespace hstanleycrow\EasyPHPDatatables\Formatters;

use hstanleycrow\EasyPHPDatatables\Config;

class MailtoFormatter implements IColumnFormatterGenerator
{
    public function generate(): callable
    {
        $classes = Config::instance()->getMailtoClasses();
        return function ($d, $row) use ($classes) {
            return '<span class="' . $classes . '"><a href="mailto:' . $d . '" class="btn_link">' . $d . '</span></a>';
        };
    }
}
