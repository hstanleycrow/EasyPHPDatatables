<?php

namespace hstanleycrow\EasyPHPDatatables\Tests\Fixtures;

use hstanleycrow\EasyPHPDatatables\Column;
use hstanleycrow\EasyPHPDatatables\ActionButton;

class Product
{
    public string $dbTable = 'products';
    public string $model = 'product';
    public string $primaryKey = 'id';

    public function getColumns(): array
    {
        return [
            new Column('Id', '`a`.`id`', 'id'),
            new Column('Name', '`a`.`name`', 'name'),
            new Column('Price', '`a`.`price`', 'price', 'money'),
        ];
    }

    public function getButtons(): array
    {
        return [
            new ActionButton('edit', 'Edit', '`a`.`id`', 'id', 'edit', 'Edit'),
        ];
    }

    public function getJoinQuery(): string
    {
        return "FROM `products` AS `a`";
    }

    public function getExtraCondition(): string
    {
        return "";
    }
}
