<?php

namespace hstanleycrow\EasyPHPDatatables;

class Config
{
    private static ?self $instance = null;

    private const DEFAULTS = [
        'definitions_namespace' => 'hstanleycrow\\DatatablesDefinitions',
        'language' => 'en',
        'page_length' => 25,
        'table_classes' => 'table',
        'error_message' => 'Error loading table data. Please update. If the error persists contact the developer.',
        'date_format' => 'd/m/Y',
        'datetime_format' => 'd/m/Y H:i',
        'time_format' => 'H:i:s',
        'money_symbol' => '$',
        'decimal_separator' => '.',
        'thousand_separator' => ',',
        'decimals' => 2,
        'percentage_position' => 'right',
        'mailto_classes' => 'btn btn-link',
        'image_classes' => 'img img-responsive',
    ];

    private const ENV_MAP = [
        'definitions_namespace' => 'DT_DEFINITIONS_NAMESPACE',
        'language' => 'DT_LANGUAGE',
        'page_length' => 'DT_PAGE_LENGTH',
        'table_classes' => 'DT_TABLE_CLASSES',
        'error_message' => 'DT_ERROR_MESSAGE',
        'date_format' => 'DT_DATE_FORMAT',
        'datetime_format' => 'DT_DATETIME_FORMAT',
        'time_format' => 'DT_TIME_FORMAT',
        'money_symbol' => 'DT_MONEY_SYMBOL',
        'decimal_separator' => 'DT_DECIMAL_SEPARATOR',
        'thousand_separator' => 'DT_THOUSAND_SEPARATOR',
        'decimals' => 'DT_DECIMALS',
        'percentage_position' => 'DT_PERCENTAGE_POS',
        'mailto_classes' => 'DT_MAILTO_CLASSES',
        'image_classes' => 'DT_IMAGE_CLASSES',
    ];

    private array $values;

    public function __construct(array $overrides = [])
    {
        $this->values = $overrides + self::DEFAULTS;
    }

    public static function instance(): self
    {
        return self::$instance ??= self::fromEnv();
    }

    public static function fromEnv(): self
    {
        $overrides = [];
        foreach (self::ENV_MAP as $key => $envKey) {
            if (isset($_ENV[$envKey])) {
                $overrides[$key] = $_ENV[$envKey];
            }
        }
        return new self($overrides);
    }

    public static function use(self $config): void
    {
        self::$instance = $config;
    }

    public static function reset(): void
    {
        self::$instance = null;
    }

    public function getDefinitionsNamespace(): string
    {
        return (string) $this->values['definitions_namespace'];
    }

    public function getLanguage(): string
    {
        return (string) $this->values['language'];
    }

    public function getPageLength(): int
    {
        return (int) $this->values['page_length'];
    }

    public function getTableClasses(): string
    {
        return (string) $this->values['table_classes'];
    }

    public function getErrorMessage(): string
    {
        return (string) $this->values['error_message'];
    }

    public function getDateFormat(): string
    {
        return (string) $this->values['date_format'];
    }

    public function getDatetimeFormat(): string
    {
        return (string) $this->values['datetime_format'];
    }

    public function getTimeFormat(): string
    {
        return (string) $this->values['time_format'];
    }

    public function getMoneySymbol(): string
    {
        return (string) $this->values['money_symbol'];
    }

    public function getDecimalSeparator(): string
    {
        return (string) $this->values['decimal_separator'];
    }

    public function getThousandSeparator(): string
    {
        return (string) $this->values['thousand_separator'];
    }

    public function getDecimals(): int
    {
        return (int) $this->values['decimals'];
    }

    public function getPercentagePosition(): string
    {
        return (string) $this->values['percentage_position'];
    }

    public function getMailtoClasses(): string
    {
        return (string) $this->values['mailto_classes'];
    }

    public function getImageClasses(): string
    {
        return (string) $this->values['image_classes'];
    }
}
