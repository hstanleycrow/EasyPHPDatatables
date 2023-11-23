<?php

namespace hstanleycrow\EasyPHPDatatables;

use hstanleycrow\EasyPHPDatatables\Formatters\ColumnFormatter;

/**
 * Clase encargada de generar instancias de DatatableDefinition.
 * 
 * Utiliza el patrón Factory para crear instancias de DatatableDefinition basadas en el nombre de la tabla y los botones deshabilitados.
 */
class DefinitionGenerator
{
    public function generate(string $tableName, ?array $dtDisabledIdButtons = []): DatatableDefinition
    {

        $tableName = ucwords($tableName);
        $dtDefinition = "{$tableName}";
        $definitionClassInstance = (new CallDatatableDefinition())->getInstance($dtDefinition);
        $dbTableDetails = new DatatableDBTableDetails($definitionClassInstance->dbTable, $definitionClassInstance->primaryKey);
        $formatter = new ColumnFormatter();
        $builder = new DatatableDefinitionBuilder($definitionClassInstance->model);

        $definition = new DatatableDefinition(
            $dbTableDetails,
            $formatter,
            $builder,
            $definitionClassInstance->model
        );
        $definition->buildDefinition($definitionClassInstance, $dtDisabledIdButtons);

        return $definition;
    }
}
