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
        $endpoint = $options->getAjaxUrl();
        if ($endpoint === '') {
            throw new \Exception(
                'Ajax URL is required. Call setAjaxUrl() with the URL of your server-processing endpoint.'
            );
        }

        $ajax_url = $endpoint . '?dtDefinition=' . $dtDefinition;
        if ($props->getDisabledButtons())
            $ajax_url .= '&db=' . implode(',', $props->getDisabledButtons());

        $errorMessage = self::toJs($options->getLoadingErrorMessage());
        $selector = self::toJs('#' . $props->getTableId());
        $ajax = self::toJs($ajax_url);

        $order = $options->getDefaultOrder();
        $orderJs = $order === null
            ? ''
            : "
            order: [[" . $order['column'] . ", " . self::toJs($order['dir']) . "]],
            ";

        return "<script>
        $.fn.dataTable.ext.errMode = () => alert(" . $errorMessage . ");
        new DataTable(" . $selector . ", {
            ajax: " . $ajax . ","
            . Language::inlineConfig($options->getLanguage())
            . $orderJs .
            "processing: true,
            serverSide: true,
            'pageLength': " . $options->getRowsPerPage() . "
        });
    </script>";
    }

    private static function toJs(string $value): string
    {
        return json_encode(
            $value,
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
        );
    }
}
