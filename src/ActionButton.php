<?php

namespace hstanleycrow\EasyPHPDatatables;

class ActionButton
{
    public function __construct(
        public readonly string $buttonId,
        public readonly string $viewName,
        public readonly string $dbName,
        public readonly string $field,
        public readonly string $path,
        public readonly string $buttonText,
        public readonly ?string $buttonClass = null,
    ) {
    }

    public static function fromArray(array $button): self
    {
        return new self(
            $button['button_id'],
            $button['view_name'],
            $button['db_name'],
            $button['field'],
            $button['path'],
            $button['buttonText'],
            $button['buttonClass'] ?? null,
        );
    }

    public static function normalize(self|array $button): self
    {
        return $button instanceof self ? $button : self::fromArray($button);
    }
}
