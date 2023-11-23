<?php

namespace hstanleycrow\EasyPHPDatatables;

class DatatableOptions
{
    protected string $language = "en";
    protected int $rowsPerPage = 25;
    protected string $loadingErrorMessage = '';


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

    private function getDefaultLanguage(): string
    {
        return DatatableConfig::getDefaultLanguage();
    }

    private function getDefaultRowsPerPage(): int
    {
        return DatatableConfig::getDefaultRowsPerPage();
    }

    private function getDefaultErrorMessage(): string
    {
        return DatatableConfig::getDefaultErrorMessage();
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
