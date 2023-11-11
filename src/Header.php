<?php

namespace hstanleycrow\EasyPHPDatatables;

class Header
{
    protected array $columnNames = [];

    public static function setColumnNames(array $columnNames): static
    {
        self::$columnNames = $columnNames;
        return new static();
    }
}
