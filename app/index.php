<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

// Middleware
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;

use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';
require_once './middlewares/Logger.php';

require_once './controllers/UsuarioController.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// seteo path base
$app->setBasePath('/slim-php-mysql-heroku/app');

// Add parse body
$app->addBodyParsingMiddleware();

$app->get('[/]', function (Request $request, Response $response) {    
    $response->getBody()->write("Slim Framework 4 PHP");
    return $response;
})->add(\Logger::class . ':LogOperacion');

// Routes 
$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UsuarioController::class . ':TraerTodos');
    $group->get('/{usuario}', \UsuarioController::class . ':TraerUno');
    $group->post('[/]', \UsuarioController::class . ':CargarUno');
})->add(\Logger::class . ':LogOperacion');


//ejercicio 1
$app->group('/credenciales', function (RouteCollectorProxy $group) {

  $group->get('[/]', function (Request $request, Response $response) {    
    $response->getBody()->write("Credenciales por GET");
    return $response;
  });

  $group->post('[/]', \UsuarioController::class . ':CargarUnoBis');
})->add(\Logger::class . ':LogOperacion')
->add(\Logger::class . ':VerificarCredencial'); // middleware para esta ruta

//ejercicio 2
$app->group('/json', function (RouteCollectorProxy $group) {

  $group->get('[/]', function (Request $request, Response $response) {    
    $response->getBody()->write("json por GET");
    return $response;
  }); 

  $group->post('[/]', function (Request $request, Response $response) {    
    $response->getBody()->write("json por post");
    return $response;
  });

})->add(\Logger::class . ':VerificarJson'); // middleware para esta ruta


////////////////////////////


$app->run();

// php -S localhost:666 -t app


