<?php

require '../vendor/autoload.php';

session_start();

// Define slim settings
$settings = require '../app/settings.php';

// Create and configure Slim app
$app = new \Slim\App($settings); // Création d'un objet $app avec la classe App contenue dans le namespace Slim

// Define slim settings
require '../app/dependencies.php';

// Define app routes
require '../app/routes.php';

// Run app
$app->run();