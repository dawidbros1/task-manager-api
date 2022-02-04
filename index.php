<?php

require_once __DIR__ . "/autoload.php";
require_once __DIR__ . '/Utils/debug.php';
require_once __DIR__ . "/functions.php";

$config = require_once __DIR__ . "/config/config.php";

use Controller\Controller;

Controller::initConfiguration($config);

$controller = getController();
$controller->run();
