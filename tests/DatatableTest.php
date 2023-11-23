<?php

use PHPUnit\Framework\TestCase;
use Dotenv\Dotenv;
use hstanleycrow\EasyPHPDatatables\Datatable;

class DatatableTest extends TestCase
{
    /**
     * @covers Datatable::__construct
     */
    public function testCreateDatatable()
    {
        // Coloca aquí el código de creación del Datatable
        $dotEnv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotEnv->load();

        $datatable = new Datatable(DTDefinition: 'user', dtDisabledIdButtons: ['delete']);

        // Verifica que el objeto Datatable se haya creado correctamente
        $this->assertInstanceOf(Datatable::class, $datatable);

        // Agrega más aserciones según sea necesario
    }
    /**
     * @covers Datatable::render
     */
    public function testRenderDatatable()
    {
        // Configurar el entorno necesario para la prueba
        $dotEnv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotEnv->load();

        // Instanciar la clase Datatable
        $datatable = new Datatable(DTDefinition: 'user', dtDisabledIdButtons: ['delete']);

        // Llamar al método que quieres probar
        $renderedOutput = $datatable->render();

        // Realizar afirmaciones para verificar el resultado
        $this->assertStringContainsString('<table', $renderedOutput);
        $this->assertStringContainsString('</table>', $renderedOutput);
    }
}
