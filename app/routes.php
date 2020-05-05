<?php
//
//use Psr\Http\Message\ResponseInterface as Response;
//use Psr\Http\Message\ServerRequestInterface as Request;
//use Slim\App;
//use Slim\Factory\AppFactory;

//$loader = new \Twig\Loader\FilesystemLoader(__DIR__);
//$twig = new \Twig\Environment($loader);

//use Slim\App;

require_once 'controller/Session.php';
require_once 'controller/Home.php';
require_once 'controller/AppController.php';
require_once 'controller/Admin.php';
require_once 'controller/Patient.php';
require_once '../vendor/autoload.php';

$app = new Slim\App(['settings' => ['displayErrorDetails' =>true]]);
//
//$app = AppFactory:: create();
//

$app->get('/', Home::class . ':index');

$app->get('/adminLogin', Home::class . ':adminLogin');

$app->get('/adminRegister', Home::class . ':adminRegister');

$app->get('/registerPatient', Home::class . ':registerPatient');

$app->get('/adminHome', Home::class . ':admin');

$app->get('/doctorHome', Home::class . ':doctor'); //add codes for this

$app->get('/dataUpdate', Home::class . ':behabitData');

$app->get('/analyzeData', Home::class . ':analyzeData');

$app->get('/doctorLogin', Home::class . ':doctorLogin');

$app->get('/scheduleApp', Home::class . ':scheduleAppointment');

$app->get('/medicalHistory', Home::class . ':medicalHistory');

$app->get('/viewHabits', Home::class . ':viewHabits');

$app->get('/doctorRegister', Home::class . ':doctorRegister');


$app->group('/v1/admin', function () use ($app){

        $app->get('/', Admin::class . ':index');

        $app->post('/login', Admin::class . ':loginAdmin');

        $app->post('/new', Admin::class . ':newAdmin');

        $app->post('/data', Admin::class . ':dataUpdate'); // update Behabit_Doctive data

});

$app->group('/v1/patient', function () use ($app){

    $app->post('/register', Patient::class . ':registerPatient' );

    $app->get('/view', Patient::class . ':viewPatients');

    $app->post('/habits', Patient::class . ':behabitUpdate' );

    $app->post('/app', Patient::class . ':scheduleApp' );

    $app->get('/viewAppointment', Patient::class . ':viewAppointment');

    $app->get('/behabitUsers', Patient::class . ':behabitUser');

    $app->post('/image', Patient::class . ':fileUpload');

    $app->post('/history', Patient::class . ':medHistory');

    $app->get('/viewMedHistory', Patient::class . ':viewMedHistory');

    $app->get('/userHabits', Patient::class . ':viewHabits');
});

$app->group('/v1/doctor', function () use ($app){

//    $app->get('/', Doctor::class . ':index');

    $app->post('/login', Doctor::class . ':loginDoctor');

    $app->get('/view', Doctor::class . ':viewPatients');

    $app->get('/behabitUsers', Doctor::class . ':behabitUser');

    $app->get('/viewAppointment', Doctor::class . ':viewAppointment');


    $app->post('/new', Doctor::class . ':newDoctor');

});
//

//
//$app->get('/adminLogin', Home::class . ':adminLogin');
//
//$app->get('/admin', Home::class . ':admin');