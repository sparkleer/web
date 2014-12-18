<?php
require_once(ROOT.DS."libs".DS."dibi".DS."dibi.php");
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model
 *
 * @author Paja
 */
class Model {
     public $db;
    
     
    
    
    public function __construct() {
	$this->connectDB();  
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}
    }
    
    private function connectDB() {
        $config = array(
            "driver" => "pdo",
            "dsn" => "mysql:host=localhost;dbname=mydb;charset=utf8",
            "username" => "root",
            "password" => "",
        );
        dibi::connect($config, "main");
        dibi::activate("main");
    }
    
 
    

}
