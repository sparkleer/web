<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Controller
 */
class Controller {
    /**
     * 
     */
    public function __construct() {
      
        if (!isset($_SESSION)) {
            session_start();
        }
    }

    /**
     * 
     * @param type $name
     */
    protected function load_model($name) {
        $mName = ucfirst($name) . "_Model";
        $model = new $mName;
        $varName = $name . "_model";
        $this->$varName = $model;
    }
    /**
     * 
     * @param type $name
     * @param type $data
     */
    protected function view($name, $data = null) {
        
        $view = new View($name,$data);
        //$this->$vName = $view;
    }
    /**
     * Tato funkce se stara o presmerovani.
     * Pokud neni vyplnena adresa tak se hodi na uvodni stranu index.
     * 
     * @param type $url
     */
    protected function redirect($url = NULL) {
        $file = explode("?",$_SERVER["REQUEST_URI"]);
        if(!isset($url)) {
           header('Location: http://'. $_SERVER["HTTP_HOST"] . $file[0]);
            
        }
        else {
            header('Location: http://'. $_SERVER["HTTP_HOST"] . $file[0] . "?url=" . $url);
        }
        
    }
    
    /**
     * Zkontroluje zda je nastavena session pro uzivatele, kteri se chteji prihlasit
     */
    protected function check_session_exists() {
        if(!isset($_SESSION["uzivatel"])) {  
            $this->redirect("uzivatele/prihlaseni&logged=no");
        } else {
	    $ip = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
	    if ($_SESSION["uzivatel"]["ip"] != $ip) {
		session_destroy();
		$this->redirect("uzivatele/prihlaseni&logged=no");
	    }
	}
	
    }
    
    protected function checkAdmin($die = true) {
		if ($_SESSION["uzivatel"]["is_admin"] == 1) {
			return true;

		} else {
			
			if ($die)
				die("Teeeda.. Bububu! Ty nejsi žádný admin!");
			return false;
			
		}
    }
    
    protected function flashMessage($type, $title, $text = "") {
	$_SESSION["flashMessage"]["type"] = $type;
	$_SESSION["flashMessage"]["title"] = $title;
        $_SESSION["flashMessage"]["text"] = $text;
    }

}
