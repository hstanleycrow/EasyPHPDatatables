<?php

namespace hstanleycrow\EasyPHPDatatables;

class DatatableColumn
{
    protected array $definitionColumnsList = [];
    protected array $columnsName = [];

    public function addDataColumns(array $columns): self
    {
        foreach ($columns as $column) {
            $format = (new DatatableColumnFormatter($column['format']))->generate();
            $this->addDataColumn($column['db_name'], $column['field'], $column['as'] ?? null, $format);
        }

        $this->buildColumnsName($columns);
        return $this;
    }

    private function buildColumnsName(array $columns): void
    {
        $this->columnsName = array_map(
            fn ($column) => $column['view_name'],
            $columns
        );
    }
    private function addDataColumn(string $dbField, string $field, string $as = null,  ?callable $columnFormat): self
    {
        $column = [
            'db' => $dbField,
            'field' => $field,
            'dt' => DatatableHelper::$addedColumns,
        ];
        if (!empty($as)) {
            $column['as'] = $as;
        }
        if (!empty($columnFormat)) {
            $column['formatter'] = $columnFormat;
        }

        $this->definitionColumnsList[] = $column;

        DatatableHelper::incrementColumnsCount();

        return $this;
    }

    public function addDefinitionToColumnsList(array $column): self
    {
        $this->definitionColumnsList[] = $column;
        return $this;
    }

    public function addColumnsName(array $buttons, array $dtDisabledIdButtons): void
    {
        foreach ($buttons as $button) {
            if (!$this->isButtonDisabled($button['button_id'], $dtDisabledIdButtons)) {
                $this->columnsName[] = $button['view_name'];
            }
        }
    }

    public function isButtonDisabled(string $buttonId, array $dtDisabledIdButtons): bool
    {
        return in_array($buttonId, $dtDisabledIdButtons);
    }

    public function getColumnsName(): array
    {
        return $this->columnsName;
    }

    public function getDTColumnsDefinitions(): array
    {
        return $this->definitionColumnsList;
    }
}
