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

//    public function index(Request $request, Response $response){
//        if ($this->session->get('logged_id') == true){
//            echo $this->twig->render('adminHome.twig');
////            return $response->withRedirect('/v1/admin/');
////            , ['name' => $this->session->get('admin_first_name')]
//        }
//            else
//                echo $this->twig->render('adminHome.twig');
////                echo $this->twig->render('index.twig');
//    }

    public function newAdmin(Request $request, Response $response){

        $error = "Error! Please try again";
        $success = "Admin added!";
        $admin = $request->getParsedBody();
        $adName = $admin['adName'];
        $code = $admin['code'];
        $password = $admin['password'];
//        var_dump($adname);
        //INSERT INTO `admin` (`adminID`, `name`, `staff_code`, `password`) VALUES ('001', 'happy', 'A001', '123');
        $newAdmin = "INSERT INTO `admin` (`name`, `staff_code`, `password`) VALUES (?,?,?)";
        $result = $this->database->prepare($newAdmin);
        //->execute([$adname, $code, $password]);
        $result->execute([$adName, $code, $password]);

        if($result == true){
            $this->session->flash('add', ' Admin Successfully Registered!');
//            return $response->withRedirect('/adminHome');
        }
        else{
            $this->session->flash('error', 'ERROR!');
//            return $response->withRedirect('/');
        }
        echo $this->twig->render('register.twig', array('session' => $_SESSION, 'add' => $this->session->get('add'),
            'error' => $this->session->get('error'), 'adName' => $this->session->get('adName')));
    }

    public function loginAdmin(Request $request, Response $response){

        $success = "Logged In!";

        $adLogin = $request->getParsedBody();
        $code = $adLogin['code']; //striptags to avoid cross site scripting
        $password = $adLogin['password']; // name in the bracket should be the same as name in twig for each field
//        var_dump($code);
        //$password = password_hash($password1, PASSWORD_DEFAULT);
        $login = $this->database->prepare("SELECT * FROM `admin` WHERE staff_code = ? AND password = ?"); ///////////
        $result = $login->execute([$code, $password]);
        $row = $login->fetch( PDO::FETCH_ASSOC );
//        var_dump($password);

        if($row == true){

            if(password_verify($password, $adLogin['password']))
                $this->session->set('logged_id', true);
                $this->session->set('admin', $adLogin);
//                $this->session->set('adName', $row['adName']);
////            return $response->withRedirect('/v1/admin/');
            echo $this->twig->render('adminHome.twig', array('session' => $_SESSION, 'adLogin' => $adLogin, 'adName' => $this->session->get('adName')));
            //            return $response->withStatus(200)->withJson($success);
        }
        else {

            $this->session->flash('error', 'Login Unsuccessful! Please try again');
//            var_dump($code);
            echo $this->twig->render('adminLogin.twig', array('session' => $_SESSION, 'error' => $this->session->get('error')));
        }
    }

    public function createGraph(Request $request, Response $response){

        $graph = "SELECT Bpoints FROM behabit ORDER BY behabitID";
        $result = $this->database->query($graph);

        if ($result == true) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

                $data = array();
                foreach ($result as $row) {
                    $data[] = $row;
                }
            }
        }
    }


}