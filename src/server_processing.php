<?php

use hstanleycrow\EasyPHPDatatables\DatabaseConnector;

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
$schema = filter_input(INPUT_GET, 'schema', FILTER_UNSAFE_RAW);
if (empty($schema) || !is_string($schema)) {
    throw new Exception('Schema is required');
}
$namespace = $_ENV['DT_SCHEMAS_NAMESPACE'] . '\\' ?? 'hstanleycrow\DatatableSchemas\\';
$schema = $namespace . ucwords($schema) . 'Datatable';
$schemaInstance = new $schema();
$sql_details = $dbConnector->getConnectionDetails();
$table = $schemaInstance->getTable();
$primaryKey = $schemaInstance->getPrimaryKey();

$columns = $schemaInstance->getColumns();
require('ssp.class.php');

echo json_encode(
    SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns)
);
