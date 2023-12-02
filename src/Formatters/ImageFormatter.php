<?php

namespace hstanleycrow\EasyPHPDatatables\Formatters;

class ImageFormatter implements IColumnFormatterGenerator
{
    public function generate(): callable
    {
        $classes = $this->getClasses();
        return function ($d, $row) use ($classes) {
            return '<img src="' . $d . '" class="' . $classes . '" width="35" />';
        };
    }
    private function getClasses()
    {
        return $_ENV['DT_IMAGE_CLASSES'] ?? 'img img-responsive';
    }
}
