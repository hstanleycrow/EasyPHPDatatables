<?php

namespace hstanleycrow\EasyPHPDatatables;

use hstanleycrow\EasyPHPDatatables\Formatters\ColumnFormatter;

class DatatableSchema
{
    protected string $table;
    protected string $primaryKey;
    protected array $columns = [];
    protected static int $addedColumns = 0;
    protected array $columnsName = [];
    protected ColumnFormatter $formatter;

    public function __construct(string $table, string $primaryKey)
    {
        $this->table = $table;
        $this->primaryKey = $primaryKey;
        $this->formatter = new ColumnFormatter();
    }

    public function addColumn(string $dbField, string $format  = 'text'): self
    {
        $formatClass  = 'hstanleycrow\EasyPHPDatatables\Formatters\\' . ucwords($format) . 'Formatter';
        if (!$this->isValidFormatter($formatClass)) {
            throw new \InvalidArgumentException("Format '$format' is not supported.");
        }
        $columnFormat = (new $formatClass())->generate();
        $this->columns[] = [
            'db' => $dbField,
            'dt' => self::$addedColumns,
        ];
        if (!empty($columnFormat)) {
            $this->columns[self::$addedColumns]['formatter'] = $columnFormat;
        }

        self::$addedColumns++;
        return $this;
    }
    private function isValidFormatter($formatClass): bool
    {
        return class_exists($formatClass);
    }

    public function getTable(): string
    {
        return $this->table;
    }
    public function getColumnsName(): array
    {
        return $this->columnsName;
    }
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }
    public function getColumns(): array
    {
        return $this->columns;
    }
}
