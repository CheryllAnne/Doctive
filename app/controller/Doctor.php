<?php

use PHPMailer\PHPMailer\SMTP;
use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


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
        //$password = password_hash($password1, PASSWORD_DEFAULT);
        $login = $this->database->prepare("SELECT * FROM `doctor` WHERE staff_code = ? AND password = ?"); ///////////
        $result = $login->execute([$code, $password]);
        $row = $login->fetch( PDO::FETCH_ASSOC );

        if($row == true){

            if(password_verify($password, $drLogin['password']))
                $this->session->set('logged_id', true);
            echo $this->twig->render('doctorHome.twig', array('drLogin' => $this->session->get('drLogin')));
        }
        else {

            $this->session->flash('error', 'Login Unsuccessful! Please try again');
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
            echo $this->twig->render('doctorPatients.twig', array('i' => $count, 'patients' => $this->session->get('patients')));
        } else {
            echo $this->twig->render('doctorHome.twig');
        }
    }

    public function patientReport( Request $request, Response $response, $args ) {

        $count = 0;
        $patientID = $args['patientId'];
        $viewPatients = "Select * from `patient` WHERE patientId = ?";
//        $result = $this->database->query( $viewPatients );
        $result = $this->database->prepare( $viewPatients );
        $result->execute([$patientID]);

        if ( $result == true ) {
            while ( $row = $result->fetch( PDO::FETCH_ASSOC ) ) {
                $patients[] = $row;
                $count = $count + 1;
            }
            $this->session->set('patients', $patients);
            echo $this->twig->render('createReport.twig', array('i' => $count, 'patients' => $this->session->get('patients')));
        } else {
            echo $this->twig->render('doctorHome.twig');
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
            echo $this->twig->render('doctorPatients.twig', array('i' => $count, 'search'=>$this->session->get('search'),
                'patients' => $this->session->get('patients')));
        } else {
            echo $this->twig->render('doctorHome.twig');
        }
//
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
            echo $this->twig->render('behabitUsersDr.twig', array('i' => $count, 'behabitUsers' => $this->session->get('behabitUsers')));
        } else {
            echo $this->twig->render('adminHome.twig');
        }
    }

    public function wekaAnalyze( Request $request, Response $response )
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
            echo $this->twig->render('analyzeUsers.twig', array('i' => $count, 'behabitUsers' => $this->session->get('behabitUsers')));
        } else {
            echo $this->twig->render('adminHome.twig');
        }
    }

    public function analysisUpdate( Request $request, Response $response, $args )
    {
        $behabitID = $args['behabitID'];
        $descUpdate = $request->getParsedBody();
        $description = $descUpdate['description'];
        $update = "UPDATE behabitUsers Set description = ? WHERE behabitID = ?";
        $result = $this->database->prepare( $update )->execute( [$description, $behabitID] );

        if ( $result == true ) {
            $this->session->flash('add', ' Description Successfully Updated!');
        } else {
            $this->session->flash('error', 'ERROR!');
        }
        return $response->withRedirect('/v1/doctor/wekaAnalyze');
//        echo $this->twig->render('analyzeUsers.twig', array('session' => $_SESSION, 'add' => $this->session->get('add'),
//            'error' => $this->session->get('error')));
    }

    public function viewAppointment( Request $request, Response $response )
    {

        $count = 0;
        $viewAppt = "Select * from `appointment` order by date, time ";
        $result = $this->database->query($viewAppt);

        if ($result == true) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $appointments[] = $row;
                $count = $count + 1;
            }
            $this->session->set('appointments', $appointments);
            echo $this->twig->render('doctorAppointment.twig', array('i' => $count, 'appointments' => $this->session->get('appointments')));
        } else {
            echo $this->twig->render('doctorHome.twig');
        }
    }

    public function deleteAppt( Request $request, Response $response, $args ) {
        $success = "Successfully deleted";
        $error = "Post doesn't exist";
        $appNo = $args['appNo'];
        $deleteAppt = "Delete from `appointment` where appNo = ?";
        $result = $this->database->prepare( $deleteAppt ) or die( $this->database->error );
        //$result->bindValue( 'room_id', $roomID );
        $result->execute([$appNo]);

        if ( $result == true ) {

            return $response->withRedirect('/v1/doctor/viewAppointment');
        } else {
            echo $this->twig->render('doctorAppointment.twig');

        }
    }

    public function sendPrescription( Request $request, Response $response, $args ){

            $sendPres = $request->getParsedBody();
//        $fromEmail = $sendPres['fromEmail'];
            $toEmail = $sendPres['toEmail'];
            $subjectName = $sendPres['subject'];
            $message = $sendPres['message'];

            $this->mail->IsSMTP();
            $this->mail->SMTPDebug = 0;
            $this->mail->SMTPAuth = true;
            $this->mail->SMTPSecure = 'tls'; //tls or ssl
            $this->mail->Host = 'smtp.gmail.com';
            $this->mail->Port = 587; //465 or 587 or 25
            $this->mail->Username = 'cheryllanne07@gmail.com';
            $this->mail->Password = 'blfxczqhlaamymcc';

            $this->mail->setFrom('cheryllanne07@gmail.com', 'Doctive');
            $this->mail->isHTML(true);
            $this->mail->Subject = $subjectName;
            $this->mail->Body = $message;
            $this->mail->addAddress($toEmail);

            $result =$this->mail->send();

        if($result == true)
        {
            $this->session->flash('add', 'Email Successfully Sent');
        }
        else{
            $this->session->flash('error', ' Email Not Sent');
        }
        echo $this->twig->render('prescription.twig', array('session' => $_SESSION, 'result' => $result, 'add' => $this->session->get('add'),
            'error' => $this->session->get('error')));
    }

    public function updateHistory( Request $request, Response $response, $args ){

        $update = $request->getParsedBody();
        $patientID = $args['patientID'];
        $comment = $update['comment'];
        $prescription = $update['prescription'];

        $updateHistory = "UPDATE `history` SET comment = ?, prescription = ? WHERE patientID = ?";
        $result = $this->database->prepare( $updateHistory )->execute([$patientID, $comment, $prescription ]);

        if ( $result == true ) {
            $this->session->flash('add', ' Medical History Updated');

        } else {
            $this->session->flash('error', 'ERROR!');

        }
        echo $this->twig->render('medicalHistory.twig', array('session' => $_SESSION, 'add' => $this->session->get('add'),
            'error' => $this->session->get('error')));
    }

}