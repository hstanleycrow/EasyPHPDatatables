<?php

namespace hstanleycrow\EasyPHPDatatables;

class Column
{
    public function __construct(
        public readonly string $viewName,
        public readonly string $dbName,
        public readonly string $field,
        public readonly string $format = 'text',
        public readonly ?string $as = null,
    ) {
    }

    public static function fromArray(array $column): self
    {
        return new self(
            $column['view_name'],
            $column['db_name'],
            $column['field'],
            $column['format'] ?? 'text',
            $column['as'] ?? null,
        );
    }

    public static function normalize(self|array $column): self
    {
        return $column instanceof self ? $column : self::fromArray($column);
    }
}
