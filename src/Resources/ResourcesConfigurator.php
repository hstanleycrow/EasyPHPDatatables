<?php

namespace hstanleycrow\EasyPHPDatatables\Resources;

class ResourcesConfigurator
{
    protected Frameworks $frameworks;
    protected array $cssResources = ['https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css'];
    protected array $jsResources = ['https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js'];
    protected string $framework = 'bootstrap5';

    public function __construct(Frameworks $frameworks)
    {
        $this->frameworks = $frameworks;
        $this->setFramework();
    }
    public function setFramework(string $framework = 'bootstrap5'): self
    {
        switch ($framework) {
            case $this->frameworks::BOOTSTRAP3:
                $this->cssResources = $cssResources = [
                    'https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap.min.css',
                ];
                $this->jsResources = [
                    'https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap.min.js',
                ];
                break;
            case $this->frameworks::BOOTSTRAP4:
                $this->cssResources = [
                    'https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css',
                ];
                $this->jsResources = [
                    'https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js',
                ];
                break;
            case $this->frameworks::BOOTSTRAP5:
                $this->cssResources = [
                    'https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css',
                ];
                $this->jsResources = [
                    'https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js',
                ];
                break;
            case $this->frameworks::BULMA:
                $this->cssResources = [
                    'https://cdn.datatables.net/1.13.7/css/dataTables.bulma.min.css',
                ];
                $this->jsResources = [
                    'https://cdn.datatables.net/1.13.7/js/dataTables.bulma.min.js',
                ];
                break;
            case $this->frameworks::FOUNDATION:
                $this->cssResources = [
                    'https://cdn.datatables.net/1.13.7/css/dataTables.foundation.min.css',
                ];
                $this->jsResources = [
                    'https://cdn.datatables.net/1.13.7/js/dataTables.foundation.min.js',
                ];
                break;
            case $this->frameworks::JQUERY:
                $this->cssResources = [
                    'https://cdn.datatables.net/1.13.7/css/dataTables.jqueryui.min.css',
                ];
                $this->jsResources = [
                    'https://cdn.datatables.net/1.13.7/js/dataTables.jqueryui.min.js',
                ];
                break;
            case $this->frameworks::SEMANTIC:
                $this->cssResources = [
                    'https://cdn.datatables.net/1.13.7/css/dataTables.semanticui.min.css',
                ];
                $this->jsResources = [
                    'https://cdn.datatables.net/1.13.7/js/dataTables.semanticui.min.js',
                ];
                break;
            default:
                throw new \Exception('Invalid framework specified');
        }
        return $this;
    }

    /**
     * Get the value of cssResources
     */
    public function getCssResources()
    {
        return $this->cssResources;
    }

    /**
     * Get the value of jsResources
     */
    public function getJsResources()
    {
        return $this->jsResources;
    }
}
