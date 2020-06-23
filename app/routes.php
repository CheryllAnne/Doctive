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

$app->get('/doctorHome', Home::class . ':doctor');

$app->get('/analystHome', Home::class . ':analyst');

$app->get('/dataUpdate', Home::class . ':behabitData');

$app->get('/analyzeData', Home::class . ':analyzeData');

$app->get('/tableau', Home::class . ':tableau');

$app->get('/doctorLogin', Home::class . ':doctorLogin');

$app->get('/scheduleApp', Home::class . ':scheduleAppointment');

$app->get('/medicalHistory', Home::class . ':medicalHistory');

$app->get('/viewHabits', Home::class . ':viewHabits');

$app->get('/doctorRegister', Home::class . ':doctorRegister');

$app->get('/highlightAppts', Home::class . ':highlightAppointment');

$app->get('/prescription', Home::class . ':prescription');

$app->get('/analystLogin', Home::class . ':analystLogin');



$app->group('/v1/admin', function () use ($app){

//    $app->get('/', Admin::class . ':index');

    $app->post('/login', Admin::class . ':loginAdmin');

    $app->post('/new', Admin::class . ':newAdmin');

    $app->get('/setApp/{patientId}', Admin::class . ':appointmentForm');

    $app->get('/update/{patientId}', Admin::class . ':updatePRecords');

    $app->get('/updateEmgcy/{icNo}', Admin::class . ':updateERecords');

    $app->post('/deletePatient/{patientId}', Admin::class . ':deletePatient');

//    $app->get('/logout', Admin::class . ':logout');

});

$app->group('/v1/patient', function () use ($app){

    $app->post('/register', Patient::class . ':registerPatient' );

    $app->post('/update/{patientId}', Patient::class . ':updatePatient' );

    $app->get('/view', Patient::class . ':viewPatients');

    $app->post('/habits', Patient::class . ':behabitUpdate' );

    $app->post('/app', Patient::class . ':scheduleApp' );

    $app->get('/viewAppointment', Patient::class . ':viewAppointment');

    $app->get('/behabitUsers', Patient::class . ':behabitUser');

    $app->post('/history', Patient::class . ':medHistory');

    $app->get('/viewMedHistory/{icNo}', Patient::class . ':viewMedHistory');

    $app->get('/userHabits/{behabitID}', Patient::class . ':viewHabits');

    $app->get('/searchPatient', Patient::class . ':searchPatient');

    $app->get('/viewEmergency', Patient::class . ':viewEmergency');

    $app->post('/deleteEmgcy/{eID}', Patient::class . ':deleteEmergency');

    $app->post('/updateEmergency/{patientIcNo}', Patient::class . ':updateEmergency' );
});


$app->group('/v1/doctor', function () use ($app){

    $app->post('/login', Doctor::class . ':loginDoctor');

    $app->get('/view', Doctor::class . ':viewPatients');

    $app->get('/patientReport/{patientId}', Doctor::class . ':patientReport');

    $app->get('/behabitUsers', Doctor::class . ':behabitUser');

    $app->get('/viewAppointment', Doctor::class . ':viewAppointment');

    $app->get('/searchPatient', Doctor::class . ':searchPatient');

    $app->post('/deleteAppt/{appNo}', Doctor::class . ':deleteAppt');

    $app->post('/sendPres', Doctor::class . ':sendPrescription');

    $app->get('/wekaAnalyze', Doctor::class . ':wekaAnalyze');

    $app->post('/analysisUpdate/{behabitID}', Doctor::class . ':analysisUpdate');

    $app->post('/new', Doctor::class . ':newDoctor');

});

$app->group('/v1/analyst', function () use ($app) {

    $app->post('/login', Analyst::class . ':loginAnalyst');

    $app->get('/behabitUsers', Analyst::class . ':behabitUser');

    $app->post('/image', Analyst::class . ':decisionTree');

    $app->post('/image/{behabitID}', Analyst::class . ':decisionTree');

    $app->get('/update/{behabitID}', Analyst::class . ':analysisForm');

});
