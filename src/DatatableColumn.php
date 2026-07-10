<?php

namespace hstanleycrow\EasyPHPDatatables;

class DatatableColumn
{
    protected array $definitionColumnsList = [];
    protected array $columnsName = [];
    protected int $columnIndex = 0;

    public function addDataColumns(array $columns): self
    {
        $columns = array_map([Column::class, 'normalize'], $columns);

        foreach ($columns as $column) {
            $format = (new DatatableColumnFormatter($column->format))->generate();
            $this->addDataColumn($column->dbName, $column->field, $column->as, $format);
        }

        $this->buildColumnsName($columns);
        return $this;
    }

    private function buildColumnsName(array $columns): void
    {
        $this->columnsName = array_map(
            fn (Column $column) => $column->viewName,
            $columns
        );
    }
    private function addDataColumn(string $dbField, string $field, ?string $as = null, ?callable $columnFormat = null): self
    {
        $column = [
            'db' => $dbField,
            'field' => $field,
            'dt' => $this->reserveColumnIndex(),
        ];
        if (!empty($as)) {
            $column['as'] = $as;
        }
        if (!empty($columnFormat)) {
            $column['formatter'] = $columnFormat;
        }

        $this->definitionColumnsList[] = $column;

        return $this;
    }

    public function reserveColumnIndex(): int
    {
        return $this->columnIndex++;
    }

    public function addDefinitionToColumnsList(array $column): self
    {
        $this->definitionColumnsList[] = $column;
        return $this;
    }

    public function addColumnsName(array $buttons, array $dtDisabledIdButtons): void
    {
        foreach ($buttons as $button) {
            $button = ActionButton::normalize($button);
            if (!$this->isButtonDisabled($button->buttonId, $dtDisabledIdButtons)) {
                $this->columnsName[] = $button->viewName;
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
