<?php

use hstanleycrow\EasyPHPDatatables\SSP;
use hstanleycrow\EasyPHPDatatables\DatabaseConnector;
use hstanleycrow\EasyPHPDatatables\CallDatatableDefinition;

/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simple to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

require_once '../vendor/autoload.php';

$dbConnector = new DatabaseConnector();
$dtDefinition = filter_input(INPUT_GET, 'dtDefinition', FILTER_UNSAFE_RAW);
if (empty($dtDefinition) || !is_string($dtDefinition)) {
    throw new Exception('Datatable Definition is required');
}
/*$namespace = $_ENV['DT_SCHEMAS_NAMESPACE'] . '\\' ?? 'hstanleycrow\DatatableSchemas\\';
$schema = $namespace . ucwords($schema) . 'Datatable';
$schemaInstance = new $schema();*/
$definitionClassInstance = (new CallDatatableDefinition())->getInstance($dtDefinition);
$sql_details = $dbConnector->getConnectionDetails();
$table = $definitionClassInstance->getTable();
$primaryKey = $definitionClassInstance->getPrimaryKey();

$columns = $definitionClassInstance->getDTColumnsDefinitions();
$joinQuery = $definitionClassInstance->getJoinQuery() ?? null;
$extraCondition = $definitionClassInstance->getExtraCondition() ?? null;
#require('ssp.class.php');
#require('ssp.php');

echo json_encode(
    SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraCondition)
);
