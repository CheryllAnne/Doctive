<?php

use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message;
error_reporting(E_ALL ^ E_NOTICE);

require_once 'AppController.php';
require_once 'Session.php';
require_once 'Admin.php';

class Patient extends AppController
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

    public function registerPatient( Request $request, Response $response ) {

        $register = $request->getParsedBody();
        $name = $register['name'];
        $icNo = $register['icNo'];
        $age = $register['age'];
        $sex = $register['sex'];
        $status = $register['status'];
        $height = $register['height'];
        $weight = $register['weight'];
        $medCard = $register['medCard'];
        $medCardNo = $register['medCardNo'];
        $address = $register['address'];
        $email = $register['email'];
        $medHistory = $register['medHistory'];
        //$password = $register['password'];
        $newPatient = "Insert into patient (name, icNo, age, sex, status, height, weight, medCard, medCardNo, address, email, medHistory) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
        $result = $this->database->prepare( $newPatient )->execute( [ $name, $icNo, $age, $sex, $status, $height, $weight, $medCard, $medCardNo, $address, $email, $medHistory ] );

        if ( $result == true ) {
            $this->session->flash('add', ' Patient Successfully Registered!');
//            echo $this->twig->render('patients.twig', array('firstName' => $this->session->get('first_name')));
        } else {
            $this->session->flash('error', 'ERROR!');
//            return $response->withRedirect( '/login' );
        }
        echo $this->twig->render('registerPatient.twig', array('session' => $_SESSION, 'add' => $this->session->get('add'),
            'error' => $this->session->get('error')));
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
                echo $this->twig->render('patients.twig', array('i' => $count, 'patients' => $this->session->get('patients'), 'roomName' => $this->session->get('roomName'),
                    'roomType' => $this->session->get('roomType'), 'description' => $this->session->get('description')));
        } else {
            echo $this->twig->render('adminHome.twig');
        }
    }

    public function behabitUpdate( Request $request, Response $response ) {

        $update = $request->getParsedBody();
        $name = $update['name'];
        $username = $update['username'];
        $icNo = $update['icNo'];
        $age = $update['age'];
        $sex = $update['sex'];
        $date = $update['date'];

        $newUpdate = "Insert into habits (name, username, icNo, age, sex, date ) VALUES (?,?,?,?,?,?)";
        $result = $this->database->prepare( $newUpdate )->execute([$name, $username, $icNo, $age, $sex, $date]);

        if ( $result == true ) {
            $this->session->flash('add', ' Patient habits progress Successfully Registered!');
//            echo $this->twig->render('patients.twig', array('firstName' => $this->session->get('first_name')));
        } else {
            $this->session->flash('error', 'ERROR!');
//            return $response->withRedirect( '/login' );
        }
        echo $this->twig->render('dataUpdate.twig', array('session' => $_SESSION, 'add' => $this->session->get('add'),
            'error' => $this->session->get('error')));
//var_dump($update);
    }

    //not functioning
    public function fileUpload( Request $request, Response $response, $args){

        $files = $request->getUploadedFiles();
        if (empty($files['file_input'])) {
            throw new Exception('No file has been send');
        }
        $myFile = $files['file_input'];
        if ($myFile->getError() === UPLOAD_ERR_OK) {
            $uploadFileName = $myFile->getClientFilename();
            $myFile->moveTo('../public/res/img' . $uploadFileName);
        }

        $newFile = "Insert into images (decisionTree ) VALUES (?)";
        $result = $this->database->prepare( $newFile )->execute( [ $myFile] );

        if ( $result == true ) {
            $this->session->flash('add', ' Image Successfully Added!');
//            echo $this->twig->render('patients.twig', array('firstName' => $this->session->get('first_name')));
        } else {
            $this->session->flash('error', 'ERROR!');
//            return $response->withRedirect( '/login' );
        }
        echo $this->twig->render('dataUpdate.twig', array('session' => $_SESSION, 'add' => $this->session->get('add'),
            'error' => $this->session->get('error')));

    }

    public function scheduleApp( Request $request, Response $response ){

        $appointment = $request->getParsedBody();
        $name = $appointment['name'];
        $icNo = $appointment['icNo'];
        $drName = $appointment['drName'];
        $date = $appointment['date'];
        $time = $appointment['time'];
        $remarks = $appointment['remarks'];

        $newApp = "Insert into appointment (name, icNo, drName, date, time, remarks) VALUES (?,?,?,?,?,?)";
        $result = $this->database->prepare( $newApp )->execute([ $name, $icNo, $drName, $date, $time, $remarks ]);

        if ( $result == true ) {
            $this->session->flash('add', ' Appointment has been scheduled. ');

        } else {
            $this->session->flash('error', 'ERROR!');

        }
        echo $this->twig->render('scheduleApp.twig', array('session' => $_SESSION, 'add' => $this->session->get('add'),
            'error' => $this->session->get('error')));
    }

    //do some edits
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
            echo $this->twig->render('appointment.twig', array('i' => $count, 'appointments' => $this->session->get('appointments'), 'roomName' => $this->session->get('roomName'),
                'roomType' => $this->session->get('roomType'), 'description' => $this->session->get('description')));
        } else {
            echo $this->twig->render('adminHome.twig');
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
            echo $this->twig->render('behabitUsers.twig', array('i' => $count, 'behabitUsers' => $this->session->get('behabitUsers'), 'roomName' => $this->session->get('roomName'),
                'roomType' => $this->session->get('roomType'), 'description' => $this->session->get('description')));
        } else {
            echo $this->twig->render('adminHome.twig');
        }
    }

    public function viewHabits( Request $request, Response $response )
    {
        $count = 0;
        $userHabits = "Select * from `behabit` order by date ";
        $result = $this->database->query($userHabits);

        if ($result == true) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $habits[] = $row;
                $count = $count + 1;
            }
            $this->session->set('habits', $habits);
            echo $this->twig->render('viewHabits.twig', array('i' => $count, 'habits' => $this->session->get('habits'), 'roomName' => $this->session->get('roomName'),
                'roomType' => $this->session->get('roomType'), 'description' => $this->session->get('description')));
        } else {
            echo $this->twig->render('adminHome.twig');
        }
    }

    public function medHistory( Request $request, Response $response ){

        $history = $request->getParsedBody();
        $name = $history['name'];
        $icNo = $history['icNo'];
        $lastVisit = $history['last_visit'];
        $currentVisit = $history['current_visit'];
        $status = $history['status'];
        $medCardNo = $history['medCardNo'];
        $comment = $history['comment'];
        $prescription = $history['prescription'];

        $medHistory = "INSERT INTO `history` (name, icNo, last_visit, current_visit, status, medCardNo, comment, prescription ) VALUES (?,?,?,?,?,?,?,?)";
        $result = $this->database->prepare( $medHistory )->execute([ $name, $icNo, $lastVisit, $currentVisit, $status, $medCardNo, $comment, $prescription ]);

        if ( $result == true ) {
            $this->session->flash('add', ' Medical History Updated');

        } else {
            $this->session->flash('error', 'ERROR!');

        }
        echo $this->twig->render('medicalHistory.twig', array('session' => $_SESSION, 'add' => $this->session->get('add'),
            'error' => $this->session->get('error')));
    }

    public function viewMedHistory( Request $request, Response $response )
    {
        $count = 0;
        $viewHistory = "Select * from `history` order by patientID ";
        $result = $this->database->query($viewHistory);

        if ($result == true) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $medHistory[] = $row;
                $count = $count + 1;
            }
            $this->session->set('medHistory', $medHistory);
            echo $this->twig->render('viewMedHistory.twig', array('i' => $count, 'medHistory' => $this->session->get('medHistory'), 'roomName' => $this->session->get('roomName'),
                'roomType' => $this->session->get('roomType'), 'description' => $this->session->get('description')));
        } else {
            echo $this->twig->render('adminHome.twig');
        }
    }


}