<?php

use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
error_reporting(E_ALL ^ E_NOTICE);

require_once 'AppController.php';
require_once 'Session.php';

class Analyst extends AppController
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

    public function loginAnalyst(Request $request, Response $response){

        $AnLogin = $request->getParsedBody();
        $code = $AnLogin['code']; //striptags to avoid cross site scripting
        $password = $AnLogin['password']; // name in the bracket should be the same as name in twig for each field
        $login = $this->database->prepare("SELECT * FROM `analyst` WHERE staff_code = ? AND password = ?"); ///////////
        $result = $login->execute([$code, $password]);
        $row = $login->fetch( PDO::FETCH_ASSOC );

        if($row == true){
            if(password_verify($password, $AnLogin['password']))
                $this->session->set('logged_id', true);
            $this->session->set('analyst', $AnLogin);
            echo $this->twig->render('analystHome.twig', array('session' => $_SESSION, 'AnLogin' => $AnLogin));
        }
        else {
            $this->session->flash('error', 'Login Unsuccessful! Please try again');
            echo $this->twig->render('analystLogin.twig', array('session' => $_SESSION, 'error' => $this->session->get('error')));
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
            echo $this->twig->render('analyst_behabit.twig', array('i' => $count, 'behabitUsers' => $this->session->get('behabitUsers')));
        } else {
            echo $this->twig->render('analystHome.twig');
        }
    }

    public function analysisForm( Request $request, Response $response, $args ) {

        $count = 0;
        $behabitID = $args['behabitID'];
        $behabitUsers = "Select * from `behabitUsers` WHERE behabitID = ?";
        $result = $this->database->prepare( $behabitUsers );
        $result->execute([$behabitID]);

        if ( $result == true ) {
            while ( $row = $result->fetch( PDO::FETCH_ASSOC ) ) {
                $bUser[] = $row;
                $count = $count + 1;
            }
            $this->session->set('bUser', $bUser);
            echo $this->twig->render('dataUpdate.twig', array('i' => $count, 'bUser' => $this->session->get('bUser')));
        } else {
            echo $this->twig->render('analystHome.twig');
        }
    }

    public function decisionTree(Request $request, Response $response, $args){

        $behabitID = $args['behabitID'];
        $files = $request->getParsedBody();
        $description = $files['description'];
        $pname = rand(1000,10000)."-".$_FILES["file_input"]["name"];
        $tname = $_FILES["file_input"]["tmp_name"];
        $uploads_dir = '../public/uploads';
        move_uploaded_file($tname, $uploads_dir.'/'.$pname);
//        $newFile = "Insert into image(description,image) VALUES (?,?) ";
        $newFile = "UPDATE behabitUsers Set description = ?, image = ? WHERE behabitID = ?";
        $result = $this->database->prepare( $newFile )->execute( [$description, $pname, $behabitID] );

        if ( $result == true ) {
            $this->session->flash('add', ' Weka Analysis Successfully Added!');
        } else {
            $this->session->flash('error', 'ERROR!');
        }
        echo $this->twig->render('dataUpdate.twig', array('session' => $_SESSION, 'result' => $result, 'add' => $this->session->get('add'),
            'error' => $this->session->get('error')));

    }

}