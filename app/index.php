<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';
// require_once './middlewares/Logger.php';

require_once './controllers/UsuarioController.php';
require_once './controllers/TiendaController.php';
require_once './controllers/VentasController.php';
// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes
//$app->group('/usuarios', function (RouteCollectorProxy $group) {
//    $group->get('[/]', \UsuarioController::class . ':TraerTodos');
//    $group->get('/{usuario}', \UsuarioController::class . ':TraerUno');
//    $group->post('[/]', \UsuarioController::class . ':CargarUno');
//  });

$app->group('/tienda', function (RouteCollectorProxy $group){
    $group->post('/alta', \TiendaController::class . ':CargarUno');
    $group->post('/consultar',\TiendaController::class . ':ConsultarProducto');
});

$app->group('/ventas', function (RouteCollectorProxy $group){
  $group->post('/alta', \VentasController::class . ':CargarUno');
  $group->group('/consultar',function (RouteCollectorProxy $group){
    $group->get('/productos/vendidos',\VentasController::class . ':ConsultarProductosVendidos');
    $group->get('/ventas/porUsuario',\VentasController::class . ':ObtenerVentasPorUsuario');
    $group->get('/ventas/porProducto',\VentasController::class . ':ObtenerVentasPorProducto');
    $group->get('/productos/entreValores',\VentasController::class . ':ObtenerVentasEntreValores');
    $group->get('/ventas/ingresos',\VentasController::class . ':ObtenerGananciasPorDia');
    $group->get('/productos/masVendido',\VentasController::class . ':ObtenerElProductoMasVendido');
  });
  $group->put('/modificar', \VentasController::class . ':ModificarUno');
});

$app->get('[/]', function (Request $request, Response $response) {    
    $payload = json_encode(array("mensaje" => "Slim Framework 4 PHP"));
    
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
