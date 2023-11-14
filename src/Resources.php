<?php

namespace hstanleycrow\EasyPHPDatatables;

class Resources
{
    protected static array $cssResources = ['https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css'];
    protected static array $jsResources = ['https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js'];
    protected static string $framework = 'bootstrap5';

    public static function setFramework(string $framework = 'bootstrap5'): static
    {
        switch ($framework) {
            case 'bootstrap3':
                self::$cssResources = [
                    'https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap.min.css',
                ];
                self::$jsResources = [
                    'https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap.min.js',
                ];
                break;
            case 'bootstrap4':
                self::$cssResources = [
                    'https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css',
                ];
                self::$jsResources = [
                    'https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js',
                ];
                break;
            case 'bootstrap5':
                self::$cssResources = [
                    'https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css',
                ];
                self::$jsResources = [
                    'https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js',
                ];
                break;
            case 'bulma':
                self::$cssResources = [
                    'https://cdn.datatables.net/1.13.7/css/dataTables.bulma.min.css',
                ];
                self::$jsResources = [
                    'https://cdn.datatables.net/1.13.7/js/dataTables.bulma.min.js',
                ];
                break;
            case 'foundation':
                self::$cssResources = [
                    'https://cdn.datatables.net/1.13.7/css/dataTables.foundation.min.css',
                ];
                self::$jsResources = [
                    'https://cdn.datatables.net/1.13.7/js/dataTables.foundation.min.js',
                ];
                break;
            case 'jquery':
                self::$cssResources = [
                    'https://cdn.datatables.net/1.13.7/css/dataTables.jqueryui.min.css',
                ];
                self::$jsResources = [
                    'https://cdn.datatables.net/1.13.7/js/dataTables.jqueryui.min.js',
                ];
                break;
            case 'semantic':
                self::$cssResources = [
                    'https://cdn.datatables.net/1.13.7/css/dataTables.semanticui.min.css',
                ];
                self::$jsResources = [
                    'https://cdn.datatables.net/1.13.7/js/dataTables.semanticui.min.js',
                ];
                break;
            default:
                throw new \Exception('Invalid framework specified');
        }
        return new static;
    }
    public static function autoLoadCssResources(): void
    {
        foreach (self::$cssResources as $cssResource) {
            echo '<link rel="stylesheet" href="' . $cssResource . '" crossorigin="anonymous">';
        }
    }
    public static function autoLoadJsResources(): void
    {
        echo '<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>';
        foreach (self::$jsResources as $jsResource) {
            echo '<script src="' . $jsResource . '" crossorigin="anonymous"></script>';
        }
    }

    public static function autoLoadDatatableJS(string $id, string $dtDefinition, string $language, int $rowsPerPage, string $errorMessage): void
    {
        $ajax_url = self::getPathToServerProcessingFile() . '?dtDefinition=' . $dtDefinition;
        echo "<script>
        $.fn.dataTable.ext.errMode = () => alert('" . $errorMessage . "');
        new DataTable('#" . $id . "', {
            ajax: '" . $ajax_url . "',"
            . Language::setLanguage($language)->autoLoadLanguageURL() .
            "processing: true,
            serverSide: true,
            'pageLength': " . $rowsPerPage . "
        });
    </script>";
    }

    private static function getPathToServerProcessingFile(): string
    {
        $base_url = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'];
        $pathToServerProcessing = $_ENV['DT_PATH_TO_SERVER_PROCESSING_FILE'] ?? "";
        return $base_url . $pathToServerProcessing . 'src/server_processing.php';
    }
}
