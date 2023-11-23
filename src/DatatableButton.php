<?php

namespace hstanleycrow\EasyPHPDatatables;

use hstanleycrow\EasyPHPDatatables\Resources\Resources;

class DatatableButton
{

    public function __construct(private DatatableColumn $datatableColumn, private string $model, private array $dtDisabledIdButtons = [])
    {
    }

    public function addActionButtons(array $buttons, array $dtDisabledIdButtons): self
    {
        $model = strtolower(str_replace(__NAMESPACE__, '', __CLASS__));
        foreach ($buttons as $button) {
            if (!$this->datatableColumn->isButtonDisabled($button['button_id'], $dtDisabledIdButtons)) {
                $columnFormat = (new DatatableButtonFormatter($model, $button['path'], $button['buttonText'], $button['buttonClass']))->generate();

                $this->addActionButtonToColumnList(
                    $button['db_name'],
                    $button['field'],
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
            'dt' => DatatableHelper::$addedColumns,
            'formatter' => $columnFormat,
        ];

        $this->datatableColumn->addDefinitionToColumnsList($column);
        DatatableHelper::incrementColumnsCount();
        return $this;
    }
}
