<?php

namespace hstanleycrow\EasyPHPDatatables;

class DatatableHelper
{
    public static int $addedColumns = 0;

    public static function incrementColumnsCount(): void
    {
        self::$addedColumns++;
    }
}
