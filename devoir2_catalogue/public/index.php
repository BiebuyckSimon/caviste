<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $file = __DIR__ . $_SERVER['REQUEST_URI'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

session_start();

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Register middleware
require __DIR__ . '/../src/middleware.php';



// Inclusion et chargement de Twig
include_once(__DIR__.'/../vendor/twig/twig/lib/Twig/Autoloader.php');
Twig_Autoloader::register();

// Création d'un chargeur de template
$loader = new Twig_Loader_Filesystem(__DIR__.'/../templates');

// Création et configuration du moteur de template Twig
$twig = new Twig_Environment($loader, [
  'cache' => false,             // Donner le chemin du dossier pour activer le cache
  'debug' => true,
  'charset' => 'utf-8',         // Valeur par défaut
  'auto_reload' => true,        // Regénère le template si la source a été modifiée
  'strict_variables' => true,   // Génère une exception si une variable du template n'est pas déclarée
  'autoescape' => true          // Valeur par défaut 
]);
$twig->addExtension(new Twig_Extension_Debug());



// Register routes
require __DIR__ . '/../src/routes.php';



// Intégration RedBean
define('LIB_PATH', __DIR__.'/../library/');
require LIB_PATH.'RedBean/rb.php';

// Connection à la DB
R::setup('mysql:host=localhost;dbname=cavavin', 'root', 'root');



// Run app
$app->run();
