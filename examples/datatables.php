<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use hstanleycrow\EasyPHPDatatables\SSP;

Dotenv::createImmutable(__DIR__ . '/..')->load();

SSP::handle();
