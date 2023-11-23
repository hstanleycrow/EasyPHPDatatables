<?php

namespace hstanleycrow\EasyPHPDatatables\Formatters;

class DeleteFormatter implements IColumnFormatterGenerator
{
    protected string $path;
    protected string $buttonText;
    public function __construct(string $path, string $buttonText)
    {
        $this->path = $path;
        $this->buttonText = $buttonText;
    }
    public function generate(): callable
    {
        return function ($d, $row) {
            return '<a href="' . $this->path . '/edit/' . $d . '" >' . $this->buttonText . '</a>';
        };
    }
}
