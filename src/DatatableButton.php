<?php

namespace hstanleycrow\EasyPHPDatatables;

class DatatableButton
{
    public function __construct(private DatatableColumn $datatableColumn, private string $model)
    {
    }

    public function addActionButtons(array $buttons, array $dtDisabledIdButtons): self
    {
        $buttons = array_map([ActionButton::class, 'normalize'], $buttons);

        foreach ($buttons as $button) {
            if (!$this->datatableColumn->isButtonDisabled($button->buttonId, $dtDisabledIdButtons)) {
                $columnFormat = (new DatatableButtonFormatter($this->model, $button->path, $button->buttonText, $button->buttonClass))->generate();

                $this->addActionButtonToColumnList(
                    $button->dbName,
                    $button->field,
                    $columnFormat
                );
            }
        }

        $this->datatableColumn->addColumnsName($buttons, $dtDisabledIdButtons);
        return $this;
    }

    private function addActionButtonToColumnList(string $dbField, string $field, callable $columnFormat): self
    {
        $column = [
            'db' => $dbField,
            'field' => $field,
            'dt' => $this->datatableColumn->reserveColumnIndex(),
            'formatter' => $columnFormat,
        ];

        $this->datatableColumn->addDefinitionToColumnsList($column);
        return $this;
    }
}
