<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use hstanleycrow\EasyPHPDatatables\SSP;

#[CoversClass(SSP::class)]
class SspHelpersTest extends TestCase
{
    public function testPluckReturnsPropertyValues(): void
    {
        $rows = [['db' => 'name'], ['db' => 'email']];
        $this->assertSame(['name', 'email'], SSP::pluck($rows, 'db'));
    }

    public function testPluckAppendsAliasWhenJoin(): void
    {
        $rows = [['db' => 't.name', 'as' => 'full_name']];
        $this->assertSame(['t.name AS full_name'], SSP::pluck($rows, 'db', true));
    }

    public function testBindRegistersPlaceholder(): void
    {
        $bindings = [];
        $key = SSP::bind($bindings, 'value', \PDO::PARAM_STR);

        $this->assertSame(':binding_0', $key);
        $this->assertCount(1, $bindings);
        $this->assertSame('value', $bindings[0]['val']);
    }

    public function testLimitBuildsClause(): void
    {
        $this->assertSame('LIMIT 10, 25', SSP::limit(['start' => 10, 'length' => 25], []));
    }

    public function testLimitIsEmptyWhenLengthIsAll(): void
    {
        $this->assertSame('', SSP::limit(['start' => 0, 'length' => -1], []));
    }

    public function testOrderBuildsClause(): void
    {
        $columns = [['db' => 'name', 'dt' => 0]];
        $request = [
            'order' => [['column' => 0, 'dir' => 'asc']],
            'columns' => [['data' => 0, 'orderable' => 'true']],
        ];

        $this->assertSame('ORDER BY `name` ASC', SSP::order($request, $columns));
    }
}
