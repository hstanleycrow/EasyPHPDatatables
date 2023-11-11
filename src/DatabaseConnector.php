<?php

namespace hstanleycrow\EasyPHPDatatables;

use Dotenv\Dotenv;

class DatabaseConnector
{
    protected $connection;
    protected static ?string $configFile = null;
    protected array $config = [];

    public function __construct()
    {

        // Intenta obtener la configuración de las variables de entorno
        $this->getEnvConfig();
        if (!$this->config) {
            // Si las variables de entorno no están configuradas, intenta cargar la configuración desde un archivo
            $fileConfig = $this->getFileConfig();
            if (!$this->config) {
                // Si no se proporciona configuración, lanza una excepción o usa un valor predeterminado
                throw new \Exception("Database configuration not found.");
            }
        }
    }

    // Métodos para obtener la configuración de variables de entorno
    private function getEnvConfig(): void
    {
        if (!class_exists('Dotenv\Dotenv'))
            return;
        Dotenv::createImmutable(__DIR__ . '/../')->load();
        if (isset($_ENV["DATABASE_HOST"])) {
            $this->config = [
                'host' => $_ENV["DATABASE_HOST"],
                'user' => $_ENV["DATABASE_USERNAME"],
                'pass' => $_ENV["DATABASE_PASSWORD"],
                'db' => $_ENV["DATABASE_NAME"],
                'charset' => 'utf8',
            ];
        }
    }
    public static function setConfigFile($configFile)
    {
        self::$configFile = $configFile;
    }
    // Métodos para obtener la configuración desde un archivo
    private function getFileConfig(): void
    {
        if (self::$configFile == null) {
            throw new \Exception("Database configuration file not specified.");
        }
        include self::$configFile;
        $this->config = $sql_details;
    }
    public function getConnectionDetails(): array
    {
        return $this->config;
    }
}
