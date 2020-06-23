<?php

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message;
error_reporting(E_ALL ^ E_NOTICE);

require_once 'AppController.php';
require_once 'Session.php';
require_once 'Admin.php';

class Behabit extends AppController
{
//    protected $database;
//    protected $reference;
//    protected $dbname = 'User';
//
//    public function __construct(){
//
//        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/secret/behabit-25402-1e63423f30a5.json');
//
//        $firebase = (new Factory)
//            ->withServiceAccount($serviceAccount)
//            ->createDatabase();
//
////        $firebase->getDatabase();
////        $firebase->create->getDatabase();
//        $database = $firebase->getDatabase();
//
//        die(print_r($database));
//
////        $factory = (new Factory)
////            ->withServiceAccount('../../public/res/secret/behabit-25402-1e63423f30a5.json')
////            // The following line is optional if the project id in your credentials file
////            // is identical to the subdomain of your Firebase project. If you need it,
////            // make sure to replace the URL with the URL of your project.
////            ->withDatabaseUri('https://behabit-25402.firebaseio.com/');
////
////        $database = $factory->createDatabase();
//    }
//
////    public function get(int $userID = NULL){
////        if(empty($userID) || !isset($userID)){
////            return FALSE;
////        }
////
////        $snapshot = $this->reference->getSnapshot();
////        $value = $snapshot->getValue();
////
//////        $value = $this->reference->getValue(); // Shortcut for $reference->getSnapshot()->getValue();
////    }
////
////    public function insert(array $data){
////
////    }
//
//    public function get(int $userID = NULL){
//        if (empty($userID) || !isset($userID)) { return FALSE; }
//
//        if ($this->database->getReference($this->dbname)->getSnapshot()->hasChild($userID)){
//            return $this->database->getReference($this->dbname)->getChild($userID)->getValue();
//        } else {
//            return FALSE;
//        }
//
//    }
//
//
//    public function insert(array $data) {
//        if (empty($data) || !isset($data)) { return FALSE; }
//
//        foreach ($data as $key => $value){
//            $this->database->getReference()->getChild($this->dbname)->getChild($key)->set($value);
//        }
//
//        return TRUE;
//    }

}

//$users = new Behabit();
//
//var_dump($users->insert([
//    '1' => 'John',
////    '2' => 'Doe',
////    '3' => 'Smith'
//]));