<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use hstanleycrow\EasyPHPDatatables\Datatable;

Dotenv::createImmutable(__DIR__ . '/..')->load();

$datatable = new Datatable(DTDefinition: 'user', dtDisabledIdButtons: []);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyPHPDatatables example</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <?= $datatable->autoLoadCssResources(); ?>
</head>

<body>
    <div class="container">
        <?= $datatable->setTableId('example')->render(); ?>
    </div>
    <?= $datatable->autoLoadJsResources(); ?>
    <?= $datatable->setAjaxUrl('datatables.php')->setDefaultOrder(4, 'desc')->autoLoadDatatableJS(); ?>
</body>

</html>
