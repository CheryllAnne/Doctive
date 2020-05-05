<?php
error_reporting(E_ALL ^ E_NOTICE);
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Kreait\Firebase\Factory;

use utility\Session;
require '../vendor/autoload.php';
include_once '../app/controller/AppController.php';
include_once '../app/controller/Session.php';
include_once '../app/controller/Admin.php'; //just added 18/02
include_once '../app/controller/Patient.php'; //just added 18/02
include_once '../app/controller/Doctor.php'; // adding this solved error : Callable 'Doctor' not defined (Runtime Exception)
include_once '../app/controller/Behabit.php';
include_once '../app/routes.php';

//$app = new \Slim\App;
//$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
//    $name = $args['name'];
//    $response->getBody()->write("Hello, $name");
//
//    return $response;
//});

$container = $app->getContainer();

$container['Session'] = function ($container)
{
    $session = new Session();
    return $session;
};

$container['Admin'] = function ($container)
{
    $session = $container->get('Session');
    $admin = new Admin($session);
    return $admin;
};

$container['Patient'] = function ($container)
{
    $session = $container->get('Session');
    $patient = new Patient($session);
    return $patient;
};

$container['Doctor'] = function ($container)
{
    $session = $container->get('Session');
    $doctor = new Doctor($session);
    return $doctor;
};

$container['Home'] = function ($container)
{
    $session = $container->get('Session');
    $home = new Home($session);
    return $home;
};

$container['AppController'] = function ($container)
{
    $session = $container->get('Session');
    $app_controller = new AppController($session);
    return $app_controller;
};

$container['Behabit'] = function ($container)
{
    $session = $container->get('Session');
    $behabit = new AppController($session);
    return $behabit;
};

$app->run();
