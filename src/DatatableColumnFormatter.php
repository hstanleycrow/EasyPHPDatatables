<?php

namespace hstanleycrow\EasyPHPDatatables;

class DatatableColumnFormatter
{

    public function __construct(private string $format  = 'text')
    {
    }

    public function generate(): callable
    {
        $formatClass  = 'hstanleycrow\EasyPHPDatatables\Formatters\\' . ucwords($this->format) . 'Formatter';
        if (!$this->isValidFormatter($formatClass)) {
            throw new \InvalidArgumentException("Format '$this->format' is not supported.");
        }
        return (new $formatClass())->generate();
    }

    private function isValidFormatter($formatClass): bool
    {
        return class_exists($formatClass);
    }
}
