<?php

namespace hstanleycrow\EasyPHPDatatables;

class CallSchema
{
    protected string $schema;

    public function __construct()
    {
    }
    /**
     * Retrieves an instance of the specified schema datatable.
     *
     * @param string $schema The name of the schema datatable.
     * @throws \Exception If the schema parameter is empty or not a string.
     * @return object An instance of the specified schema datatable.
     */
    public function getInstance(string $schema): object
    {
        if (empty($schema) || !is_string($schema)) {
            throw new \Exception('Schema is required');
        }

        $schemaClass = $this->getNamespace() . ucwords($schema) . 'Schema';

        return new $schemaClass();
    }
    private function getNamespace(): string
    {
        return $_ENV['DT_SCHEMAS_NAMESPACE'] . '\\' ?? 'hstanleycrow\DatatableSchemas\\';
    }
}
