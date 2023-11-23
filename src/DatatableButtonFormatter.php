<?php

namespace hstanleycrow\EasyPHPDatatables;

use hstanleycrow\EasyPHPDatatables\Formatters\ButtonFormatter;

class DatatableButtonFormatter
{
    #const BUTTON_FORMAT_CLASS  = 'hstanleycrow\EasyPHPDatatables\Formatters\ButtonFormatter';
    public function __construct(private $model, private $path, private $buttonText, private $buttonClass)
    {
    }

    public function generate(): callable
    {
        return (new ButtonFormatter($this->model, $this->path, $this->buttonText, $this->buttonClass))->generate();
    }
}
