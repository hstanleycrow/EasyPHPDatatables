<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use hstanleycrow\EasyPHPDatatables\Config;
use hstanleycrow\EasyPHPDatatables\Formatters\MoneyFormatter;
use hstanleycrow\EasyPHPDatatables\Formatters\DecimalFormatter;
use hstanleycrow\EasyPHPDatatables\Formatters\PercentageFormatter;
use hstanleycrow\EasyPHPDatatables\Formatters\DateFormatter;
use hstanleycrow\EasyPHPDatatables\Formatters\TimeFormatter;
use hstanleycrow\EasyPHPDatatables\Formatters\IntFormatter;
use hstanleycrow\EasyPHPDatatables\Formatters\TextFormatter;
use hstanleycrow\EasyPHPDatatables\Formatters\MailtoFormatter;
use hstanleycrow\EasyPHPDatatables\Formatters\ButtonFormatter;

#[CoversClass(MoneyFormatter::class)]
#[CoversClass(DecimalFormatter::class)]
#[CoversClass(PercentageFormatter::class)]
#[CoversClass(DateFormatter::class)]
#[CoversClass(TimeFormatter::class)]
#[CoversClass(IntFormatter::class)]
#[CoversClass(TextFormatter::class)]
#[CoversClass(MailtoFormatter::class)]
#[CoversClass(ButtonFormatter::class)]
class FormattersTest extends TestCase
{
    protected function setUp(): void
    {
        Config::use(new Config([
            'money_symbol' => 'USD ',
            'decimals' => 2,
            'decimal_separator' => '.',
            'thousand_separator' => ',',
            'date_format' => 'Y-m-d',
        ]));
    }

    protected function tearDown(): void
    {
        Config::reset();
    }

    public function testMoneyUsesSymbolAndSeparators(): void
    {
        $format = (new MoneyFormatter())->generate();
        $this->assertSame('USD 1,234,567.89', $format('1234567.891', []));
    }

    public function testDecimalUsesCorrectSeparatorOrder(): void
    {
        $format = (new DecimalFormatter())->generate();
        $this->assertSame('1,234,567.89', $format('1234567.891', []));
    }

    public function testPercentageRightPositionIsDefault(): void
    {
        $format = (new PercentageFormatter())->generate();
        $this->assertSame('12.50%', $format('12.5', []));
    }

    public function testPercentageLeftPosition(): void
    {
        Config::use(new Config(['percentage_position' => 'left']));
        $format = (new PercentageFormatter())->generate();
        $this->assertSame('%12.50', $format('12.5', []));
    }

    public function testDateUsesConfiguredFormat(): void
    {
        $format = (new DateFormatter())->generate();
        $this->assertSame('2023-10-28', $format('2023-10-28 12:00:00', []));
    }

    public function testTimeUsesConfiguredFormat(): void
    {
        Config::use(new Config(['time_format' => 'H:i']));
        $format = (new TimeFormatter())->generate();
        $this->assertSame('14:30', $format('2023-10-28 14:30:59', []));
    }

    public function testIntCastsValue(): void
    {
        $format = (new IntFormatter())->generate();
        $this->assertSame(42, $format('42.9', []));
    }

    public function testTextReturnsRawValue(): void
    {
        $format = (new TextFormatter())->generate();
        $this->assertSame('hello', $format('hello', []));
    }

    public function testMailtoBuildsAnchor(): void
    {
        $format = (new MailtoFormatter())->generate();
        $this->assertStringContainsString('mailto:a@b.com', $format('a@b.com', []));
    }

    public function testButtonBuildsHrefWithoutTrailingQuote(): void
    {
        $format = (new ButtonFormatter('user', 'edit', 'Edit'))->generate();
        $this->assertSame('<a href="user/edit/1/" >Edit</a>', $format(1, []));
    }
}
