<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use hstanleycrow\EasyPHPDatatables\SSP;

#[CoversClass(SSP::class)]
class SspCountsTest extends TestCase
{
    private function makeAssocPdo(): \PDO
    {
        if (!in_array('sqlite', \PDO::getAvailableDrivers(), true)) {
            $this->markTestSkipped('pdo_sqlite is not available');
        }

        $pdo = new \PDO('sqlite::memory:');
        // Reproduce the consumer scenario that triggered the bug (e.g. EasyPHPDBCore).
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->exec('CREATE TABLE items (id INTEGER PRIMARY KEY, name TEXT)');
        $pdo->exec("INSERT INTO items (id, name) VALUES (1, 'Alpha'), (2, 'Beta'), (3, 'Gamma')");

        return $pdo;
    }

    private function columns(): array
    {
        return [
            ['db' => 'id', 'field' => 'id', 'dt' => 0],
            ['db' => 'name', 'field' => 'name', 'dt' => 1],
        ];
    }

    private function request(string $search = ''): array
    {
        return [
            'draw' => 1,
            'start' => 0,
            'length' => 10,
            'search' => ['value' => $search, 'regex' => 'false'],
            'order' => [['column' => 0, 'dir' => 'asc']],
            'columns' => [
                ['data' => 0, 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
                ['data' => 1, 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ],
        ];
    }

    public function testCountsAreCorrectWithFetchAssocPdo(): void
    {
        $out = SSP::simple($this->request(), $this->makeAssocPdo(), 'items', 'id', $this->columns());

        $this->assertSame(3, $out['recordsTotal']);
        $this->assertSame(3, $out['recordsFiltered']);
        $this->assertCount(3, $out['data']);
    }

    public function testFilteredCountIsCorrectWithFetchAssocPdo(): void
    {
        $out = SSP::simple($this->request('Beta'), $this->makeAssocPdo(), 'items', 'id', $this->columns());

        $this->assertSame(3, $out['recordsTotal']);
        $this->assertSame(1, $out['recordsFiltered']);
        $this->assertCount(1, $out['data']);
    }
}
