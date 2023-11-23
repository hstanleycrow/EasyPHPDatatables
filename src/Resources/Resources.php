<?php

namespace hstanleycrow\EasyPHPDatatables\Resources;

use hstanleycrow\EasyPHPDatatables\DatatableProps;
use hstanleycrow\EasyPHPDatatables\DatatableOptions;
use hstanleycrow\EasyPHPDatatables\Resources\Frameworks;
use hstanleycrow\EasyPHPDatatables\Resources\ResourcesBuilder;
use hstanleycrow\EasyPHPDatatables\Resources\ResourcesConfigurator;

class Resources
{
    protected ResourcesConfigurator $config;
    protected ResourcesBuilder $builder;
    public function __construct()
    {
        $this->config = new ResourcesConfigurator(new Frameworks());
        $this->builder = new ResourcesBuilder($this->config);
    }
    public function autoLoadCssResources(): string
    {
        return $this->builder->autoLoadCssResources();
    }
    public function autoLoadJsResources(): string
    {
        return $this->builder->autoLoadJsResources();
    }
    public function autoLoadDatatableJS(
        string $dtDefinition,
        DatatableProps $props,
        DatatableOptions $options
    ): string {
        return $this->builder->autoLoadDatatableJS($dtDefinition, $props, $options);
    }

    public function setFramework(string $framework): void
    {
        $this->config->setFramework($framework);
    }
}
