<?php

namespace hstanleycrow\DatatableSchemas;

use hstanleycrow\EasyPHPDatatables\DatatableSchema;

class UserDatatable extends DatatableSchema
{
    protected string $schema = 'User';
    protected string $table = 'users';
    protected string $primaryKey = 'id';
    protected array $columns = [];
    protected array $columnsName = ['Nombre', 'Usuario', 'Activo', 'Created at'];

    public function __construct()
    {
        parent::__construct($this->table, $this->primaryKey);
        $this->addColumns();
    }
    private function addColumns(): void
    {
        $this->addColumn('name')
            ->addColumn('username')
            ->addColumn('active')
            ->addColumn('created_at', 'date');
    }
}
