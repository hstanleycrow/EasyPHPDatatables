<?php

namespace hstanleycrow\EasyPHPDatatables;

class Datatable
{
    protected string $DTDefinition;
    private string $tableHeader = "";
    protected string $tableId = "list";
    protected string $CssClasses = "";
    protected array $columnNames = [];
    protected string $language = "en";
    protected int $rowsPerPage = 25;
    protected string $loadingErrorMessage = '';

    public function __construct(string $DTDefinition)
    {
        $this->DTDefinition = $DTDefinition;
        $this->language = $this->getDefaultLanguage();
        $this->rowsPerPage = $this->getDefaultRowsPerPage();
        $this->CssClasses = $this->getDefaultCssClasses();
        $this->loadingErrorMessage = $this->getDefaultErrorMessage();
        $this->setColumnNames();
    }
    private function getDefaultLanguage(): string
    {
        return DatatableConfig::getDefaultLanguage();
    }

    private function getDefaultRowsPerPage(): int
    {
        return DatatableConfig::getDefaultRowsPerPage();
    }

    private function getDefaultCssClasses(): string
    {
        return DatatableConfig::getDefaultCssClasses();
    }

    private function getDefaultErrorMessage(): string
    {
        return DatatableConfig::getDefaultErrorMessage();
    }

    public function addCssClass(string $class): self
    {
        $this->CssClasses .= $class;
        return $this;
    }
    public function setTableId(string $tableId): self
    {
        $this->tableId = $tableId;
        return $this;
    }
    public function setDTLanguage(string $language): self
    {
        $this->language = $language;
        return $this;
    }
    public function setDTRowsPerPage(int $rowsPerPage = 25): self
    {
        $this->rowsPerPage = $rowsPerPage;
        return $this;
    }
    public function render(): string
    {
        $table = '<table id="' . $this->tableId . '" class="' . $this->CssClasses . '">';
        $table .= $this->renderHeader();
        $table .= '</table>';
        return $table;
    }
    private function renderHeader(): string
    {
        $this->tableHeader .= '<thead>';
        $this->tableHeader .= '<tr>';
        foreach ($this->columnNames as $columnName) :
            $this->tableHeader .= '<th>' . $columnName . '</th>';
        endforeach;
        $this->tableHeader .= '</tr>';
        $this->tableHeader .= '</thead>';
        return $this->tableHeader;
    }
    public function autoLoadDatatableJS(): void
    {
        Resources::autoLoadDatatableJS($this->tableId, $this->DTDefinition, $this->language, $this->rowsPerPage, $this->loadingErrorMessage);
    }
    public function getCSSResources(): void
    {
        Resources::autoLoadCssResources();
    }
    public function getJSResources(): void
    {
        Resources::autoLoadJsResources();
    }
    private function setColumnNames(): void
    {
        $definitionClassInstance = (new CallDatatableDefinition())->getInstance($this->DTDefinition);
        $this->columnNames = $definitionClassInstance->getColumnsName();
    }
}
