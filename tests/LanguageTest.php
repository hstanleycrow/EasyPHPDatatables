<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use hstanleycrow\EasyPHPDatatables\Resources\Language;

#[CoversClass(Language::class)]
class LanguageTest extends TestCase
{
    public function testEnglishReturnsEmptyConfig(): void
    {
        $this->assertSame('', Language::inlineConfig('en'));
    }

    public function testSpanishReturnsInlineValidJson(): void
    {
        $output = Language::inlineConfig('es');

        $this->assertStringStartsWith('language: {', $output);
        $this->assertStringEndsWith('},', $output);

        $json = rtrim(substr($output, strlen('language: ')), ',');
        $this->assertNotNull(json_decode($json));
    }

    public function testInvalidLanguageThrows(): void
    {
        $this->expectException(\Exception::class);
        Language::inlineConfig('fr');
    }
}
