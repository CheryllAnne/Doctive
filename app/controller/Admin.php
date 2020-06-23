<?php

use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
error_reporting(E_ALL ^ E_NOTICE);

require_once 'AppController.php';
require_once 'Session.php';


class Admin extends AppController
{
    protected $database;
    protected $value = array();

    public function __get( $name )
    {
        // TODO: Implement __get() method.
        return $this->value[$name];
    }

    public function __construct( $session ) {
        parent::__construct( $session );
    }

    public function newAdmin(Request $request, Response $response){

        $admin = $request->getParsedBody();
        $adName = $admin['adName'];
        $code = $admin['code'];
        $password = $admin['password'];
        $newAdmin = "INSERT INTO `admin` (`name`, `staff_code`, `password`) VALUES (?,?,?)";
        $result = $this->database->prepare($newAdmin);
        $result->execute([$adName, $code, $password]);

        if($result == true){
            $this->session->flash('add', ' Admin Successfully Registered!');
        }
        else{
            $this->session->flash('error', 'ERROR!');
        }
        echo $this->twig->render('register.twig', array('session' => $_SESSION, 'add' => $this->session->get('add'),
            'error' => $this->session->get('error'), 'adName' => $this->session->get('adName')));
    }

    public function loginAdmin(Request $request, Response $response){

        $adLogin = $request->getParsedBody();
        $code = $adLogin['code']; //striptags to avoid cross site scripting
        $password = $adLogin['password']; // name in the bracket should be the same as name in twig for each field
        $login = $this->database->prepare("SELECT * FROM `admin` WHERE staff_code = ? AND password = ?"); ///////////
        $result = $login->execute([$code, $password]);
        $row = $login->fetch( PDO::FETCH_ASSOC );

        if($row == true){
            if(password_verify($password, $adLogin['password']))
                $this->session->set('logged_id', true);
                $this->session->set('admin', $adLogin);
            echo $this->twig->render('adminHome.twig', array('session' => $_SESSION, 'adLogin' => $adLogin));
        }
        else {
            $this->session->flash('error', 'Login Unsuccessful! Please try again');
            echo $this->twig->render('adminLogin.twig', array('session' => $_SESSION, 'error' => $this->session->get('error')));
        }
    }

    public function appointmentForm( Request $request, Response $response, $args ) {

        $count = 0;
        $patientID = $args['patientId'];
        $setApp = "Select * from `patient` WHERE patientId = ?";
        $result = $this->database->prepare( $setApp );
        $result->execute([$patientID]);

        if ( $result == true ) {
            while ( $row = $result->fetch( PDO::FETCH_ASSOC ) ) {
                $patients[] = $row;
                $count = $count + 1;
            }
            $this->session->set('patients', $patients);
            echo $this->twig->render('scheduleApp.twig', array('i' => $count, 'patients' => $this->session->get('patients')));
        } else {
            echo $this->twig->render('adminHome.twig');
        }
    }

    public function updatePRecords( Request $request, Response $response, $args ) {

        $count = 0;
        $patientId = $args['patientId'];
        $viewPatients = "Select * from `patient` WHERE patientId = ?";
        $result = $this->database->prepare( $viewPatients );
        $result->execute([$patientId]);

        if ( $result == true ) {
            while ( $row = $result->fetch( PDO::FETCH_ASSOC ) ) {
                $patients[] = $row;
                $count = $count + 1;
            }
            $this->session->set('patients', $patients);
            echo $this->twig->render('patientUpdate.twig', array('i' => $count, 'patients' => $this->session->get('patients')));
        } else {
            echo $this->twig->render('adminHome.twig');
        }
    }

    public function updateERecords( Request $request, Response $response, $args ) {

        $count = 0;
        $patientIC = $args['icNo'];
        $updateERecords = "Select * from `emergency` WHERE patientIcNo = ?";
        $result = $this->database->prepare( $updateERecords );
        $result->execute([$patientIC]);

        if ( $result == true ) {
            while ( $row = $result->fetch( PDO::FETCH_ASSOC ) ) {
                $emergency[] = $row;
                $count = $count + 1;
            }
            $this->session->set('emergency', $emergency);

            echo $this->twig->render('emergencyUpdate.twig', array('i' => $count, 'emergency' => $this->session->get('emergency')));
        } else {
            echo $this->twig->render('adminHome.twig');
        }

    }

    public function deletePatient( Request $request, Response $response, $args ) {

        $patientId = $args['patientId'];
        $deletePatient = "Delete from `patient` where patientId = ?";
        $result = $this->database->prepare( $deletePatient ) or die( $this->database->error );
        $result->execute([$patientId]);

        if ( $result == true ) {
            return $response->withRedirect('/v1/patient/view');
        } else {
            echo $this->twig->render('adminHome.twig');
        }
    }

}