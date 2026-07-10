<?php

namespace hstanleycrow\EasyPHPDatatables;

class DatatableOptions
{
    protected string $language = "en";
    protected int $rowsPerPage = 25;
    protected string $loadingErrorMessage = '';
    protected string $ajaxUrl = '';


    public function __construct()
    {
        $this->language = $this->getDefaultLanguage();
        $this->rowsPerPage = $this->getDefaultRowsPerPage();
        $this->loadingErrorMessage = $this->getDefaultErrorMessage();
    }
    public function setDTLanguage(string $language): self
    {
        $this->language = $language;
        return $this;
    }
    public function setDTRowsPerPage(int $rowsPerPage = 25): self
    {
        $this->rowsPerPage = $rowsPerPage;
        return $this;
    }
    public function setAjaxUrl(string $ajaxUrl): self
    {
        $this->ajaxUrl = $ajaxUrl;
        return $this;
    }
    public function getAjaxUrl(): string
    {
        return $this->ajaxUrl;
    }

    private function getDefaultLanguage(): string
    {
        return Config::instance()->getLanguage();
    }

    private function getDefaultRowsPerPage(): int
    {
        return Config::instance()->getPageLength();
    }

    private function getDefaultErrorMessage(): string
    {
        return Config::instance()->getErrorMessage();
    }

    /**
     * Get the value of language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Get the value of rowsPerPage
     */
    public function getRowsPerPage()
    {
        return $this->rowsPerPage;
    }

    /**
     * Get the value of loadingErrorMessage
     */
    public function getLoadingErrorMessage()
    {
        return $this->loadingErrorMessage;
    }
}
