<?php

namespace hstanleycrow\EasyPHPDatatables\Resources;

use hstanleycrow\EasyPHPDatatables\DatatableProps;
use hstanleycrow\EasyPHPDatatables\DatatableOptions;

class ResourcesBuilder
{
    protected ResourcesConfigurator $config;
    public function __construct(ResourcesConfigurator $config)
    {
        $this->config = $config;
    }

    public function autoLoadCssResources(): string
    {
        $output = '';
        foreach ($this->config->getCssResources() as $cssResource) {
            $output .= '<link rel="stylesheet" href="' . $cssResource . '" crossorigin="anonymous">';
        }
        return $output;
    }
    public function autoLoadJsResources(): string
    {
        $output = '<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>';
        foreach ($this->config->getJsResources() as $jsResource) {
            $output .= '<script src="' . $jsResource . '" crossorigin="anonymous"></script>';
        }
        return $output;
    }
    public function autoLoadDatatableJS(
        string $dtDefinition,
        DatatableProps $props,
        DatatableOptions $options
    ): string {
        $ajax_url = self::getPathToServerProcessingFile() . '?dtDefinition=' . $dtDefinition;
        if ($props->getDisabledButtons())
            $ajax_url .= '&db=' . implode(',', $props->getDisabledButtons());
        return "<script>
        $.fn.dataTable.ext.errMode = () => alert('" . $options->getLoadingErrorMessage() . "');
        new DataTable('#" . $props->getTableId() . "', {
            ajax: '" . $ajax_url . "',"
            . Language::setLanguage($options->getLanguage())->autoLoadLanguageURL() .
            "processing: true,
            serverSide: true,
            'pageLength': " . $options->getRowsPerPage() . "
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
