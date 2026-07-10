<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use hstanleycrow\EasyPHPDatatables\Column;
use hstanleycrow\EasyPHPDatatables\ActionButton;

#[CoversClass(Column::class)]
#[CoversClass(ActionButton::class)]
class ValueObjectsTest extends TestCase
{
    public function testColumnFromArrayAppliesDefaults(): void
    {
        $column = Column::fromArray([
            'view_name' => 'Name',
            'db_name' => '`a`.`name`',
            'field' => 'name',
        ]);

        $this->assertSame('Name', $column->viewName);
        $this->assertSame('text', $column->format);
        $this->assertNull($column->as);
    }

    public function testColumnNormalizeKeepsObjectAndConvertsArray(): void
    {
        $object = new Column('Name', '`a`.`name`', 'name');
        $this->assertSame($object, Column::normalize($object));

        $fromArray = Column::normalize([
            'view_name' => 'Price',
            'db_name' => '`a`.`price`',
            'field' => 'price',
            'format' => 'money',
            'as' => 'unit_price',
        ]);
        $this->assertInstanceOf(Column::class, $fromArray);
        $this->assertSame('money', $fromArray->format);
        $this->assertSame('unit_price', $fromArray->as);
    }

    public function testActionButtonFromArrayAppliesDefaults(): void
    {
        $button = ActionButton::fromArray([
            'button_id' => 'edit',
            'view_name' => 'Edit',
            'db_name' => '`a`.`id`',
            'field' => 'id',
            'path' => 'edit',
            'buttonText' => 'Edit',
        ]);

        $this->assertSame('edit', $button->buttonId);
        $this->assertNull($button->buttonClass);
    }

    public function testActionButtonNormalizeKeepsObject(): void
    {
        $button = new ActionButton('edit', 'Edit', '`a`.`id`', 'id', 'edit', 'Edit');
        $this->assertSame($button, ActionButton::normalize($button));
    }
}
