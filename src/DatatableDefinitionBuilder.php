<?php

namespace hstanleycrow\EasyPHPDatatables;

/**
 * Definición de una tabla DataTable.
 * 
 * Representa la estructura y configuración de una tabla DataTable, definiendo columnas y botones.
 * Utiliza una instancia de DefinitionGeneratorInterface para generar definiciones de columnas dinámicamente.
 */

class DatatableDefinitionBuilder
{
    protected DatatableColumn $datatableColumn;
    protected DatatableButton $datatableButton;
    protected array $dtDisabledIdButtons = [];

    public function __construct(string $model)
    {
        $this->datatableColumn = new DatatableColumn();
        $this->datatableButton = new DatatableButton($this->datatableColumn, $model);
    }
    public function addDataColumns(array $columns): self
    {
        $this->datatableColumn->addDataColumns($columns);
        return $this;
    }

    public function addActionButtons(array $buttons): self
    {
        $this->datatableButton->addActionButtons($buttons, $this->dtDisabledIdButtons);
        return $this;
    }

    public function setDisabledIdButtons(array $dtDisabledIdButtons): self
    {
        $this->dtDisabledIdButtons = $dtDisabledIdButtons;
        return $this;
    }
    public function getColumnsName(): array
    {
        return $this->datatableColumn->getColumnsName();
    }
    public function getDTColumnsDefinitions(): array
    {
        return $this->datatableColumn->getDTColumnsDefinitions();
    }
}
