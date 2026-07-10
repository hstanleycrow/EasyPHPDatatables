<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use hstanleycrow\EasyPHPDatatables\Datatable;
use hstanleycrow\EasyPHPDatatables\Config;

#[CoversClass(Datatable::class)]
class DatatableTest extends TestCase
{
    protected function setUp(): void
    {
        Config::use(new Config([
            'definitions_namespace' => 'hstanleycrow\\EasyPHPDatatables\\Tests\\Fixtures',
        ]));
    }

    protected function tearDown(): void
    {
        Config::reset();
    }

    public function testItBuildsForADefinition(): void
    {
        $this->assertInstanceOf(Datatable::class, new Datatable('product'));
    }

    public function testRenderProducesTableMarkup(): void
    {
        $html = (new Datatable('product'))->setTableId('products')->render();

        $this->assertStringContainsString('<table id="products"', $html);
        $this->assertStringContainsString('</table>', $html);
        $this->assertStringContainsString('Name', $html);
    }

    public function testAutoLoadDatatableJsRequiresAjaxUrl(): void
    {
        $this->expectException(\Exception::class);
        (new Datatable('product'))->autoLoadDatatableJS();
    }

    public function testAutoLoadDatatableJsUsesEndpointAndIsJsonEncoded(): void
    {
        $js = (new Datatable('product'))
            ->setTableId('products')
            ->setAjaxUrl('datatables.php')
            ->autoLoadDatatableJS();

        $this->assertStringContainsString('new DataTable("#products"', $js);
        $this->assertStringContainsString('ajax: "datatables.php?dtDefinition=product"', $js);
    }
}
