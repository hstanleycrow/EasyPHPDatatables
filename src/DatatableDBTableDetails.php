<?php

namespace hstanleycrow\EasyPHPDatatables;

class DatatableDBTableDetails
{
    protected string $joinQuery;
    protected string $extraCondition;
    protected string $dbTable;
    protected string $primaryKey;

    public function __construct(string $dbTable, string $primaryKey)
    {
        $this->dbTable = $dbTable;
        $this->primaryKey = $primaryKey;
    }
    public function getTable(): string
    {
        return $this->dbTable;
    }
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }
    public function getJoinQuery(): string
    {
        return $this->joinQuery;
    }
    /**
     * Get the value of extraCondition
     */
    public function getExtraCondition()
    {
        return $this->extraCondition;
    }
    /**
     * Set the value of joinQuery
     *
     * @return  self
     */
    public function setJoinQuery($joinQuery)
    {
        $this->joinQuery = $joinQuery;

        return $this;
    }

    /**
     * Set the value of extraCondition
     *
     * @return  self
     */
    public function setExtraCondition($extraCondition)
    {
        $this->extraCondition = $extraCondition;

        return $this;
    }
}
