<?php

namespace hstanleycrow\EasyPHPDatatables;

class DatabaseConnector
{
    protected static ?string $configFile = null;
    protected array $config = [];

    public function __construct()
    {
        $this->getEnvConfig();

        if (!$this->config && self::$configFile !== null) {
            $this->getFileConfig();
        }

        if (!$this->config) {
            throw new \Exception(
                'Database configuration not found. Load your DATABASE_* environment variables before calling SSP::handle(), '
                    . 'pass a PDO to SSP::handle(), or set a config file via DatabaseConnector::setConfigFile().'
            );
        }
    }

    private function getEnvConfig(): void
    {
        if (!isset($_ENV['DATABASE_HOST'])) {
            return;
        }

        $this->config = [
            'host' => $_ENV['DATABASE_HOST'],
            'user' => $_ENV['DATABASE_USERNAME'],
            'pass' => $_ENV['DATABASE_PASSWORD'],
            'db' => $_ENV['DATABASE_NAME'],
            'port' => $_ENV['DATABASE_PORT'] ?? null,
            'charset' => $_ENV['DATABASE_CHARSET'] ?? 'utf8mb4',
        ];
    }

    public static function setConfigFile(string $configFile): void
    {
        self::$configFile = $configFile;
    }

    private function getFileConfig(): void
    {
        include self::$configFile;
        $this->config = $sql_details ?? [];
    }

    public function getConnectionDetails(): array
    {
        return $this->config;
    }
}
