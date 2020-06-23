<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Home extends AppController
{
    public function __construct( $session )
    {
        parent::__construct($session);
    }

    public function __get( $name )
    {
        // TODO: Implement __get() method.
        return $this->value[$name];
    }

    public function index()
    {
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
        $twig = new Twig\Environment($loader);
        echo $twig->render('index.twig');
    }

    public function adminLogin(){
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
        $twig = new Twig\Environment($loader);
        echo $twig->render('adminLogin.twig');
    }

    public function adminRegister(){
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
        $twig = new Twig\Environment($loader);
        echo $twig->render('register.twig');
    }

    public function admin(){
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
        $twig = new Twig\Environment($loader);
        echo $twig->render('adminHome.twig', array('admin' => $this->session->get('admin'), 'adname' => $this->session->get('adname') ,
            'code' => $this->session->get('code')));
    }

    public function doctor(){
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
        $twig = new Twig\Environment($loader);
        echo $twig->render('doctorHome.twig', array('doctor' => $this->session->get('doctor'), 'adname' => $this->session->get('adname') ,
            'code' => $this->session->get('code')));
    }

    public function analyst(){
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
        $twig = new Twig\Environment($loader);
        echo $twig->render('analystHome.twig', array('doctor' => $this->session->get('doctor'), 'adname' => $this->session->get('adname') ,
            'code' => $this->session->get('code')));
    }

    public function doctorRegister(){
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
        $twig = new Twig\Environment($loader);
        echo $twig->render('registerDr.twig', array('doctor' => $this->session->get('doctor'), 'adname' => $this->session->get('adname') ,
            'code' => $this->session->get('code')));
    }

    public function registerPatient(){
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
        $twig = new Twig\Environment($loader);
        echo $twig->render('registerPatient.twig');
    }

    public function scheduleAppointment(){
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
        $twig = new Twig\Environment($loader);
        echo $twig->render('scheduleApp.twig');
    }

    public function medicalHistory(){
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
        $twig = new Twig\Environment($loader);
        echo $twig->render('medicalHistory.twig');
    }

    public function behabitData(){
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
        $twig = new Twig\Environment($loader);
        echo $twig->render('dataUpdate.twig');
    }

    public function viewHabits(){
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
        $twig = new Twig\Environment($loader);
        echo $twig->render('viewHabits.twig');
    }

    public function analyzeData(){
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
        $twig = new Twig\Environment($loader);
        echo $twig->render('analyzeUsers.twig');
    }

    public function tableau(){
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
        $twig = new Twig\Environment($loader);
        echo $twig->render('tableau.twig');
    }

    public function doctorLogin(){
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
        $twig = new Twig\Environment($loader);
        echo $twig->render('doctorLogin.twig');
    }

    public function prescription(){
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
        $twig = new Twig\Environment($loader);
        echo $twig->render('prescription.twig');
    }

    public function analystLogin(){
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
        $twig = new Twig\Environment($loader);
        echo $twig->render('analystLogin.twig');
    }



}
