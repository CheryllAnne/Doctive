<?php


use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use PHPMailer\PHPMailer\PHPMailer;

class AppController
{

    protected $database;
    protected $reference;
    protected $dbname = 'User';
    public function __construct($session)
    {
        $this->session = $session;
        $this->database = $this->connect();
        $this->twig = $this->twig();
        $this->mail = $this->phpmail();
    }

    public function connect()
    {
        $servername = "localhost:3306";
        $username = "root";
        $password = "root";

        $db = new PDO("mysql:host=$servername;dbname=doctive;charset=utf8mb4", $username, $password);
//        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        return $db;
    }

    public function twig() {
        $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__,2) . '/public/views');
        $twig = new Twig\Environment($loader);
        return $twig;
    }

    public function phpmail(){
        $mail = new PHPMailer();
        return $mail;
    }

}
