<?php

namespace hstanleycrow\EasyPHPDatatables;

use hstanleycrow\EasyPHPDatatables\Formatters\ColumnFormatter;

class DatatableDefinition
{
    protected array $definitionColumnsList = [];
    protected string $joinQuery;
    protected string $extraCondition;
    protected string $dbTable;
    protected string $primaryKey;
    protected static int $addedColumns = 0;
    protected array $columnsName = [];
    protected ColumnFormatter $formatter;

    public function __construct(string $dbTable, string $primaryKey)
    {
        $this->dbTable = $dbTable;
        $this->primaryKey = $primaryKey;
        $this->formatter = new ColumnFormatter();
    }

    public function addColumn(string $dbField, string $field, ?string $as = null, string $format  = 'text'): self
    {
        $formatClass  = 'hstanleycrow\EasyPHPDatatables\Formatters\\' . ucwords($format) . 'Formatter';
        if (!$this->isValidFormatter($formatClass)) {
            throw new \InvalidArgumentException("Format '$format' is not supported.");
        }
        $columnFormat = (new $formatClass())->generate();
        $column = [
            'db' => $dbField,
            'field' => $field,
            'dt' => self::$addedColumns,
        ];
        if (!empty($as)) {
            $column['as'] = $as;
        }
        if (!empty($columnFormat)) {
            $column['formatter'] = $columnFormat;
        }

        $this->definitionColumnsList[] = $column;

        self::$addedColumns++;

        return $this;
    }
    private function isValidFormatter($formatClass): bool
    {
        return class_exists($formatClass);
    }

    public function getTable(): string
    {
        return $this->dbTable;
    }
    public function getColumnsName(): array
    {
        return $this->columnsName;
    }
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }
    public function getDTColumnsDefinitions(): array
    {
        return $this->definitionColumnsList;
    }
    public function getJoinQuery(): string
    {
        return $this->joinQuery;
    }

    /**
     * Get the value of extraCondition
     */
    public function getExtraCondition()
    {
        return $this->extraCondition;
    }
}
