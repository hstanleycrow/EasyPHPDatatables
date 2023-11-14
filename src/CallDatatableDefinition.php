<?php

namespace hstanleycrow\EasyPHPDatatables;

class CallDatatableDefinition
{
    protected string $definition;

    public function __construct()
    {
    }
    /**
     * Retrieves an instance of the specified schema datatable.
     *
     * @param string $definition The name of the datatable definition.
     * @throws \Exception If the schema parameter is empty or not a string.
     * @return object An instance of the specified schema datatable.
     */
    public function getInstance(string $definition): object
    {
        if (empty($definition) || !is_string($definition)) {
            throw new \Exception('Definition is required');
        }

        $definitionClass = $this->getNamespace() . ucwords($definition);
        return new $definitionClass();
    }
    private function getNamespace(): string
    {
        return $_ENV['DT_SCHEMAS_NAMESPACE'] . '\\' ?? 'hstanleycrow\DatatablesDefinitions\\';
    }
}
