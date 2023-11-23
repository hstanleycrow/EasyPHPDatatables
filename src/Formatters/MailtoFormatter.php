<?php

namespace hstanleycrow\EasyPHPDatatables\Formatters;

class MailtoFormatter implements IColumnFormatterGenerator
{
    public function generate(): callable
    {
        $classes = $this->getClasses();
        return function ($d, $row) use ($classes) {
            return '<span class="' . $classes . '"><a href="mailto:' . $d . '" class="btn_link">' . $d . '</span></a>';
        };
    }
    private function getClasses(): string
    {
        return $_ENV['DT_MAILTO_CLASSES'] ?? 'btn btn-link';
    }
}
