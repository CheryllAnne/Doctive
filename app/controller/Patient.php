<?php

use utility\Session;
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
        $contactNo = $register['contactNo'];
        $eContactPerson = $register['eContactPerson'];
        $eContactRelation = $register['eContactRelation'];
        $eContactNo = $register['eContactNo'];
        $alert = $register['alert'];

        $newPatient = "Insert into patient (name, icNo, age, sex, status, height, weight, medCard, medCardNo, address, email, medHistory, contactNo) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $result = $this->database->prepare( $newPatient)->execute( [ $name, $icNo, $age, $sex, $status, $height, $weight, $medCard, $medCardNo, $address, $email, $medHistory, $contactNo ] );

        if ( $result == true ) {
            $eContact = $this->database->prepare("Insert into emergency (eContactPerson, eContactRelation, eContactNo, patientName, patientIcNo, contactNo, address, alert) VALUES (?,?,?,?,?,?,?,?)");
            $result2 = $eContact->execute([ $eContactPerson, $eContactRelation, $eContactNo, $name, $icNo, $contactNo, $address, $alert]);

            if ($result2 == true) {
                $this->session->flash('add', ' Patient Successfully Registered!');
            }else{
                $this->session->flash('error', 'ERROR in emergency!');
            }

        } else {
            $this->session->flash('error', 'ERROR!');
        }
        echo $this->twig->render('registerPatient.twig', array('session' => $_SESSION, 'result2' => $result2, 'add' => $this->session->get('add'),
            'error' => $this->session->get('error')));
    }

    public function updatePatient( Request $request, Response $response, $args ){

        $patientID = $args['patientId'];
        $update = $request->getParsedBody();
        $status = $update['status'];
        $medCard = $update['medCard'];
        $medCardNo = $update['medCardNo'];
        $address = $update['address'];
        $email = $update['email'];
        $medHistory = $update['medHistory'];

        $updatePatient = "UPDATE `patient` Set status = ?, medCard = ?, medCardNo = ?, address = ?, email = ?, medHistory = ? WHERE patientId = ?";
        $result = $this->database->prepare( $updatePatient ) or die($this->database->error);
        $result->execute([$status, $medCard, $medCardNo, $address, $email, $medHistory, $patientID]);

        if ( $result == true ) {
            $this->session->flash('add', ' Patient Records Updated');
        } else {
            $this->session->flash('error', 'ERROR!');
        }

        echo $this->twig->render('patientUpdate.twig', array('session' => $_SESSION, 'add' => $this->session->get('add'),
            'error' => $this->session->get('error'), 'result' => $result));

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
                echo $this->twig->render('adminPatients.twig', array('i' => $count, 'patients' => $this->session->get('patients')));
        } else {
            echo $this->twig->render('adminHome.twig');
        }

    }

    public function searchPatient( Request $request, Response $response) {

        $count = 0;
        $patient = $request->getQueryParams();
        $CardNo = $patient['icNo'];
        $searchPatient = "Select * from `patient` where `icNo` LIKE :icNo";
        $result = $this->database->prepare( $searchPatient );
        $result->bindValue(':icNo', '%'.$CardNo.'%');
        $result->execute();

        if ( $result == true ) {
            while ( $row = $result->fetch( PDO::FETCH_ASSOC ) ) {
                $patients[] = $row;
                $count = $count + 1;
            }
            $this->session->set('patients', $patients);;
            echo $this->twig->render('adminPatients.twig', array('i' => $count, 'search'=>$this->session->get('search'), 'patients' => $this->session->get('patients')));
        } else {
            echo $this->twig->render('adminHome.twig');
        }
//
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
        } else {
            $this->session->flash('error', 'ERROR!');
        }
        echo $this->twig->render('dataUpdate.twig', array('session' => $_SESSION, 'add' => $this->session->get('add'),
            'error' => $this->session->get('error')));
    }

    public function scheduleApp( Request $request, Response $response){

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
        echo $this->twig->render('scheduleApp.twig', array('session' => $_SESSION, 'result' => $result, 'add' => $this->session->get('add'),
            'error' => $this->session->get('error')));
    }

    public function viewAppointment( Request $request, Response $response ){

        $count = 0;
        $viewAppt = "Select * from `appointment` order by date, time ";
        $result = $this->database->query($viewAppt);

        if ($result == true) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $appointments[] = $row;
                $count = $count + 1;
            }
            $this->session->set('appointments', $appointments);
            echo $this->twig->render('adminAppointment.twig', array('i' => $count, 'appointments' => $this->session->get('appointments')));
        } else {
            echo $this->twig->render('adminHome.twig');
        }
    }

    public function behabitUser( Request $request, Response $response ){

        $count = 0;
        $behabitUser = "Select * from `behabitUsers` ";
        $result = $this->database->query($behabitUser);

        if ($result == true) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $behabitUsers[] = $row;
                $count = $count + 1;
            }
            $this->session->set('behabitUsers', $behabitUsers);
            echo $this->twig->render('behabitUsers.twig', array('i' => $count, 'behabitUsers' => $this->session->get('behabitUsers')));
        } else {
            echo $this->twig->render('adminHome.twig');
        }
    }

    public function viewHabits( Request $request, Response $response, $args ){

        $count = 0;
        $behabitID = $args['behabitID'];
        $userHabits = "Select * from `behabit` WHERE behabitID = ? order by date";
        $result = $this->database->prepare($userHabits);
        $result->execute([$behabitID]);

        if ($result == true) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $habits[] = $row;
                $count = $count + 1;
            }
            $this->session->set('habits', $habits);
            echo $this->twig->render('viewHabits.twig', array('i' => $count, 'habits' => $this->session->get('habits')));
        } else {
            echo $this->twig->render('doctorHome.twig');
        }

    }

    public function medHistory( Request $request, Response $response ){

        $history = $request->getParsedBody();
        $name = $history['name'];
        $icNo = $history['icNo'];
        $currentVisit = $history['current_visit'];
        $status = $history['status'];
        $medCardNo = $history['medCardNo'];
        $comment = $history['comment'];
        $prescription = $history['prescription'];

        $medHistory = "INSERT INTO `history` (name, icNo, current_visit, status, medCardNo, comment, prescription ) VALUES (?,?,?,?,?,?,?)";
        $result = $this->database->prepare( $medHistory )->execute([ $name, $icNo, $currentVisit, $status, $medCardNo, $comment, $prescription ]);

        if ( $result == true ) {
            $this->session->flash('add', ' Medical Report Updated ');
        } else {
            $this->session->flash('error', 'ERROR!');
        }
        echo $this->twig->render('createReport.twig', array('session' => $_SESSION, 'result' => $result, 'add' => $this->session->get('add'),
            'error' => $this->session->get('error')));
    }

    public function viewMedHistory( Request $request, Response $response, $args ){

        $count = 0;
        $patientIC = $args['icNo'];
        $viewHistory = "Select * from `history` WHERE icNo = ? order by current_visit ";
        $result = $this->database->prepare( $viewHistory );
        $result->execute([$patientIC]);

        if ($result == true) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $medHistory[] = $row;
                $count = $count + 1;
            }
            $this->session->set('medHistory', $medHistory);
            echo $this->twig->render('medicalHistory.twig', array('i' => $count, 'medHistory' => $this->session->get('medHistory')));
        } else {
            echo $this->twig->render('doctorHome.twig');
        }
    }

    public function viewEmergency( Request $request, Response $response ){

        $count = 0;
        $viewEmgcy = "Select * from `emergency` WHERE alert = 1";
        $result = $this->database->query($viewEmgcy);
        $numrows = (new PDOStatement)->rowCount($result);

        if ($result == true) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $emergency[] = $row;
                $count = $count + 1;
            }
            $this->session->set('emergency', $emergency);
            echo $this->twig->render('emergencyHome.twig', array('i' => $count, 'emergency' => $this->session->get('emergency'),
            'numrows'=> $numrows));

        } else {
            return $response->withRedirect('/');
        }
    }

    public function deleteEmergency( Request $request, Response $response, $args ){

        $eID = $args['eID'];
        $deleteEmgcy = "Delete from `emergency` where eID = ?";
        $result = $this->database->prepare( $deleteEmgcy ) or die( $this->database->error );
        $result->execute([$eID]);

        if ( $result == true ) {
            return $response->withRedirect('/v1/patient/viewEmergency');
        }
        else {
            echo $this->twig->render('index.twig');
        }
    }

    public function updateEmergency( Request $request, Response $response, $args ){

        $patientIcNo = $args['patientIcNo'];
        $update = $request->getParsedBody();
        $eContactPerson = $update['eContactPerson'];
        $eContactRelation = $update['eContactRelation'];
        $eContactNo = $update['eContactNo'];
        $alert = $update['alert'];

        $updateEmergency = "UPDATE `emergency` Set eContactPerson = ?, eContactRelation = ?, eContactNo = ?, alert = ? WHERE patientIcNo = ?";
        $result = $this->database->prepare( $updateEmergency ) or die($this->database->error);
        $result->execute([$eContactPerson, $eContactRelation, $eContactNo, $alert, $patientIcNo]);

        if ( $result == true ) {
            $this->session->flash('add', ' Emergency Contact Updated');
        } else {
            $this->session->flash('error', 'ERROR!');
        }

        echo $this->twig->render('emergencyUpdate.twig', array('session' => $_SESSION, 'add' => $this->session->get('add'),
            'error' => $this->session->get('error'), 'result' => $result));

    }


}