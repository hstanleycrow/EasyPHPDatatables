<?php

namespace hstanleycrow\EasyPHPDatatables;

class Datatable
{
    protected string $schema;
    protected string $table = "";
    private string $tableHeader = "";
    protected string $id = "list";
    protected string $class = "";
    protected array $columnNames = [];
    protected string $language = "en";
    protected int $pageLength = 25;
    protected string $loadingErrorMessage = '';

    public function __construct(string $schema)
    {
        $this->schema = $schema;
        $this->language = $this->getDefaultLanguage();
        $this->pageLength = $this->getDefaultPageLength();
        $this->class = $this->getDefaultClass();
        $this->loadingErrorMessage = $this->getDefaultErrorMessage();
    }
    private function getDefaultLanguage(): string
    {
        return $_ENV['DT_LANGUAGE'] ?? 'en';
    }
    private function getDefaultPageLength(): int
    {
        return $_ENV['DT_PAGE_LENGTH'] ?? 25;
    }
    private function getDefaultClass(): string
    {
        return $_ENV['DT_TABLE_CLASSES'] ?? 'table';
    }
    private function getDefaultErrorMessage(): string
    {
        return $_ENV['DT_ERROR_MESSAGE'] ?? 'Error loading table data. Please update. If the error persists contact the developer.';
    }

    public function setColumnNames(array $columnNames): self
    {
        $this->columnNames = $columnNames;
        return $this;
    }
    public function addClass(string $class): self
    {
        $this->class .= $class;
        return $this;
    }
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }
    public function setLanguage(string $language): self
    {
        $this->language = $language;
        return $this;
    }
    public function setPageLength(int $pageLength = 25): self
    {
        $this->pageLength = $pageLength;
        return $this;
    }
    public function render(): string
    {
        $table = '<table id="' . $this->id . '" class="' . $this->class . '">';
        $table .= $this->renderHeader();
        $table .= '</table>';
        return $table;
    }
    private function renderHeader(): string
    {
        $this->tableHeader .= '<thead>';
        $this->tableHeader .= '<tr>';
        foreach ($this->columnNames as $columnName) :
            $this->tableHeader .= '<th>' . $columnName . '</th>';
        endforeach;
        $this->tableHeader .= '</tr>';
        $this->tableHeader .= '</thead>';
        return $this->tableHeader;
    }
    public function autoLoadDatatableJS(): void
    {
        echo "<script>
        $.fn.dataTable.ext.errMode = () => alert('" . $this->loadingErrorMessage . "');
        new DataTable('#" . $this->id . "', {
            ajax: 'src/server_processing.php?schema=" . $this->schema . "',"
            . Language::setLanguage($this->language)->autoLoadLanguageURL() .
            "processing: true,
            serverSide: true,
            'pageLength': " . $this->pageLength . "
        });
    </script>";
    }
}
