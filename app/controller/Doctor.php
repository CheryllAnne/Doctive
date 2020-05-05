<?php

use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
error_reporting(E_ALL ^ E_NOTICE);

require_once 'AppController.php';
require_once 'Session.php';

class Doctor extends AppController
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

    public function loginDoctor(Request $request, Response $response){

        $drLogin = $request->getParsedBody();
        $code = $drLogin['code']; //striptags to avoid cross site scripting
        $password = $drLogin['password']; // name in the bracket should be the same as name in twig for each field
//        var_dump($code);
        //$password = password_hash($password1, PASSWORD_DEFAULT);
        $login = $this->database->prepare("SELECT * FROM `doctor` WHERE staff_code = ? AND password = ?"); ///////////
        $result = $login->execute([$code, $password]);
        $row = $login->fetch( PDO::FETCH_ASSOC );
//        var_dump($password);

        if($row == true){

            if(password_verify($password, $drLogin['password']))
                $this->session->set('logged_id', true);
//                $this->session->set('adName', $row['adName']);
////            return $response->withRedirect('/v1/admin/');
            echo $this->twig->render('doctorHome.twig', array('drLogin' => $this->session->get('drLogin')));
            //            return $response->withStatus(200)->withJson($success);
        }
        else {

            $this->session->flash('error', 'Login Unsuccessful! Please try again');
//            var_dump($code);
            echo $this->twig->render('doctorLogin.twig', array('session' => $_SESSION, 'error' => $this->session->get('error')));
        }
    }

    public function viewPatients( Request $request, Response $response ) {

        $count = 0;
        $viewPatients = "Select * from `patient` order by patientId";
        $result = $this->database->query( $viewPatients );

        if ( $result == true ) {
            while ( $row = $result->fetch( PDO::FETCH_ASSOC ) ) {
                $patients[] = $row;
                $count = $count + 1;
            }
            $this->session->set('patients', $patients);
            echo $this->twig->render('patientsDr.twig', array('i' => $count, 'patients' => $this->session->get('patients'), 'roomName' => $this->session->get('roomName'),
                'roomType' => $this->session->get('roomType'), 'description' => $this->session->get('description')));
        } else {
            echo $this->twig->render('doctorHome.twig');
        }
    }

    public function behabitUser( Request $request, Response $response )
    {
        $count = 0;
        $behabitUser = "Select * from `behabitUsers` ";
        $result = $this->database->query($behabitUser);

        if ($result == true) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $behabitUsers[] = $row;
                $count = $count + 1;
            }
            $this->session->set('behabitUsers', $behabitUsers);
            echo $this->twig->render('behabitUsersDr.twig', array('i' => $count, 'behabitUsers' => $this->session->get('behabitUsers'), 'roomName' => $this->session->get('roomName'),
                'roomType' => $this->session->get('roomType'), 'description' => $this->session->get('description')));
        } else {
            echo $this->twig->render('adminHome.twig');
        }
    }

    public function viewAppointment( Request $request, Response $response )
    {

        $count = 0;
        $viewAppt = "Select * from `appointment` order by date ";
        $result = $this->database->query($viewAppt);

        if ($result == true) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $appointments[] = $row;
                $count = $count + 1;
            }
            $this->session->set('appointments', $appointments);
            echo $this->twig->render('appointmentDr.twig', array('i' => $count, 'appointments' => $this->session->get('appointments'), 'roomName' => $this->session->get('roomName'),
                'roomType' => $this->session->get('roomType'), 'description' => $this->session->get('description')));
        } else {
            echo $this->twig->render('adminHome.twig');
        }
    }
}