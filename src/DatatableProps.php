<?php

namespace hstanleycrow\EasyPHPDatatables;

class DatatableProps
{
    protected string $tableId = "list";
    protected string $CssClasses = "";
    protected array $columnNames = [];
    protected string $DTDefinition;
    protected array $dtDisabledIdButtons = [];

    public function __construct(string $DTDefinition, ?array $dtDisabledIdButtons = [])
    {
        $this->DTDefinition = $DTDefinition;
        $this->dtDisabledIdButtons = $dtDisabledIdButtons;
        $this->CssClasses = $this->getDefaultCssClasses();
        $this->setColumnNames($DTDefinition);
    }
    public function setColumnNames(): void
    {
        $definitionClassInstance = (new DefinitionGenerator())->generate($this->DTDefinition, $this->dtDisabledIdButtons);
        $this->columnNames = $definitionClassInstance->getColumnsName();
    }

    public function getDisabledButtons()
    {
        return $this->dtDisabledIdButtons;
    }
    private function getDefaultCssClasses(): string
    {
        return DatatableConfig::getDefaultCssClasses();
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

    /**
     * Get the value of tableId
     */
    public function getTableId()
    {
        return $this->tableId;
    }

    /**
     * Get the value of CssClasses
     */
    public function getCssClasses()
    {
        return $this->CssClasses;
    }

    /**
     * Get the value of columnNames
     */
    public function getColumnNames()
    {
        return $this->columnNames;
    }
}
