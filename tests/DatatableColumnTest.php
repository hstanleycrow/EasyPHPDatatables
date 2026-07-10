<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use hstanleycrow\EasyPHPDatatables\DatatableColumn;
use hstanleycrow\EasyPHPDatatables\Column;

#[CoversClass(DatatableColumn::class)]
class DatatableColumnTest extends TestCase
{
    public function testColumnIndexesStartAtZeroPerInstance(): void
    {
        $columns = [
            ['view_name' => 'A', 'db_name' => '`a`', 'field' => 'a', 'format' => 'text'],
            ['view_name' => 'B', 'db_name' => '`b`', 'field' => 'b', 'format' => 'text'],
        ];

        $first = new DatatableColumn();
        $first->addDataColumns($columns);

        $second = new DatatableColumn();
        $second->addDataColumns($columns);

        $this->assertSame([0, 1], array_column($first->getDTColumnsDefinitions(), 'dt'));
        $this->assertSame([0, 1], array_column($second->getDTColumnsDefinitions(), 'dt'));
    }

    public function testArrayAndValueObjectColumnsProduceSameStructure(): void
    {
        $fromArrays = new DatatableColumn();
        $fromArrays->addDataColumns([
            ['view_name' => 'Id', 'db_name' => '`a`.`id`', 'field' => 'id', 'format' => 'text'],
            ['view_name' => 'Price', 'db_name' => '`a`.`price`', 'field' => 'price', 'format' => 'money'],
        ]);

        $fromObjects = new DatatableColumn();
        $fromObjects->addDataColumns([
            new Column('Id', '`a`.`id`', 'id'),
            new Column('Price', '`a`.`price`', 'price', 'money'),
        ]);

        $strip = fn (array $defs) => array_map(
            fn ($d) => ['db' => $d['db'], 'field' => $d['field'], 'dt' => $d['dt']],
            $defs
        );

        $this->assertSame(
            $strip($fromArrays->getDTColumnsDefinitions()),
            $strip($fromObjects->getDTColumnsDefinitions())
        );
        $this->assertSame(['Id', 'Price'], $fromObjects->getColumnsName());
    }

    public function testReserveColumnIndexIncrements(): void
    {
        $column = new DatatableColumn();

        $this->assertSame(0, $column->reserveColumnIndex());
        $this->assertSame(1, $column->reserveColumnIndex());
        $this->assertSame(2, $column->reserveColumnIndex());
    }
}
