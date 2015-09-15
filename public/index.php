<?php

// Get Bootstrap
require_once \realpath(__DIR__ . '/../ITC/Bootstrap.php');

// Bootstrap ITC
new \ITC\Bootstrap();

// Start front controller
$frontController = new \ITC\Presentation\FrontController();
$frontController->run();