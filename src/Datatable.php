<?php

namespace hstanleycrow\EasyPHPDatatables;

use hstanleycrow\EasyPHPDatatables\Resources\Resources;

class Datatable
{
    protected string $DTDefinition;
    private string $tableHeader = '';

    protected DatatableProps $props;
    protected DatatableOptions $options;
    protected Resources $resources;

    public function __construct(string $DTDefinition, ?array $dtDisabledIdButtons = [])
    {
        $this->DTDefinition = $DTDefinition;
        $this->props = new DatatableProps($DTDefinition, $dtDisabledIdButtons);
        $this->options = new DatatableOptions($DTDefinition);
        $this->resources = new Resources();
    }
    public function render(): string
    {
        $table = '<table id="' . $this->props->getTableId() . '" class="' . $this->props->getCssClasses() . '">';
        $table .= $this->renderHeader();
        $table .= '</table>';
        return $table;
    }
    private function renderHeader(): string
    {
        $this->tableHeader .= '<thead>';
        $this->tableHeader .= '<tr>';
        foreach ($this->props->getColumnNames() as $columnName) :
            $this->tableHeader .= '<th>' . $columnName . '</th>';
        endforeach;
        $this->tableHeader .= '</tr>';
        $this->tableHeader .= '</thead>';
        return $this->tableHeader;
    }
    public function autoLoadDatatableJS(): string
    {
        return $this->resources->autoLoadDatatableJS(
            $this->DTDefinition,
            $this->props,
            $this->options
        );
    }

    public function autoLoadCssResources(): string
    {
        return $this->resources->autoLoadCssResources();
    }
    public function autoLoadJsResources(): string
    {
        return $this->resources->autoLoadJsResources();
    }
    public function addCssClass(string $class): self
    {
        $this->props->addCssClass($class);
        return $this;
    }
    public function setTableId(string $tableId): self
    {
        $this->props->setTableId($tableId);
        return $this;
    }
    public function setDTLanguage(string $language): self
    {
        $this->options->setDTLanguage($language);
        return $this;
    }
    public function setDTRowsPerPage(int $rowsPerPage = 25): self
    {
        $this->options->setDTRowsPerPage($rowsPerPage);
        return $this;
    }
    public function setFramework(string $framework): void
    {
        $this->resources->setFramework($framework);
    }
}
