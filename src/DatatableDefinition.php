<?php

namespace hstanleycrow\EasyPHPDatatables;

use hstanleycrow\EasyPHPDatatables\Formatters\ColumnFormatter;

/**
 * Definición de una tabla DataTable.
 * 
 * Representa la estructura y configuración de una tabla DataTable, definiendo columnas y botones.
 * Utiliza una instancia de DefinitionGeneratorInterface para generar definiciones de columnas dinámicamente.
 */

class DatatableDefinition
{

    public function __construct(
        protected DatatableDBTableDetails $dbTableDetails,
        protected ColumnFormatter $formatter,
        protected DatatableDefinitionBuilder $builder,
        string $model
    ) {
    }

    public function buildDefinition(object $definitionClassInstance, array $dtDisabledIdButtons): void
    {
        $this
            ->setJoinQuery($definitionClassInstance->getJoinQuery())
            ->setExtraCondition($definitionClassInstance->getExtraCondition());

        $this->builder
            ->setDisabledIdButtons($dtDisabledIdButtons)
            ->addDataColumns($definitionClassInstance->getColumns())
            ->addActionButtons($definitionClassInstance->getButtons());
    }


    public function getColumnsName(): array
    {
        return $this->builder->getColumnsName();
    }

    public function getDTColumnsDefinitions(): array
    {
        return $this->builder->getDTColumnsDefinitions();
    }
    public function getTable(): string
    {
        return $this->dbTableDetails->getTable();
    }
    public function getPrimaryKey(): string
    {
        return $this->dbTableDetails->getPrimaryKey();
    }
    public function getJoinQuery(): string
    {
        return $this->dbTableDetails->getJoinQuery();
    }
    /**
     * Get the value of extraCondition
     */
    public function getExtraCondition()
    {
        return $this->dbTableDetails->getExtraCondition();
    }
    /**
     * Set the value of joinQuery
     *
     * @return  self
     */
    public function setJoinQuery($joinQuery)
    {
        $this->dbTableDetails->setJoinQuery($joinQuery);

        return $this;
    }

    /**
     * Set the value of extraCondition
     *
     * @return  self
     */
    public function setExtraCondition($extraCondition)
    {
        $this->dbTableDetails->setExtraCondition($extraCondition);

        return $this;
    }
}
