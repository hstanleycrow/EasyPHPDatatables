<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use hstanleycrow\EasyPHPDatatables\Config;

#[CoversClass(Config::class)]
class ConfigTest extends TestCase
{
    protected function tearDown(): void
    {
        Config::reset();
    }

    public function testReturnsDefaultsWhenNoOverrides(): void
    {
        $config = new Config();

        $this->assertSame('en', $config->getLanguage());
        $this->assertSame(25, $config->getPageLength());
        $this->assertSame('$', $config->getMoneySymbol());
        $this->assertSame(2, $config->getDecimals());
        $this->assertSame('hstanleycrow\DatatablesDefinitions', $config->getDefinitionsNamespace());
    }

    public function testOverridesTakePrecedenceAndAreCast(): void
    {
        $config = new Config([
            'language' => 'es',
            'page_length' => '50',
            'decimals' => '3',
        ]);

        $this->assertSame('es', $config->getLanguage());
        $this->assertSame(50, $config->getPageLength());
        $this->assertSame(3, $config->getDecimals());
    }

    public function testUseOverridesSharedInstanceAndResetRebuildsIt(): void
    {
        $override = new Config(['language' => 'es-MX']);

        Config::use($override);
        $this->assertSame($override, Config::instance());

        Config::reset();
        $this->assertNotSame($override, Config::instance());
    }
}
