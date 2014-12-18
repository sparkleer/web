<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Uzivatele_Controller
 *
 * @author Paja
 */
class Uzivatele_Controller extends Controller{
    
    public function __construct(){
	parent::__construct();
	$this->load_model("uzivatele");
    }
    
    public function registrace() {

		if (isset($_POST["registrace"])) {


			$validace = $this->uzivatele_model->validace_registrace($_POST);

			if ($validace === TRUE) {
				$this->uzivatele_model->zaregistruj($_POST);
				$this->redirect("uzivatele/prihlaseni/&reg=ok");
			} else {
				$data["chyba"] = $validace;
				$data["chyba_type"] = "danger";
				unset($_POST["heslo"]);
				$data["udaje"] = $_POST;


				$this->view("registrace", @$data);
			}
		} else {

			$this->view("registrace");
		}
	}

	public function prihlaseni() {
       if (isset($_SESSION["uzivatel"]))
           $this->redirect();
       
       if(isset($_POST["prihlaseni"])) {
           

         
         if($this->uzivatele_model->prihlasit($_POST)) {
             $this->redirect(DEFAULT_CONTROLLER."/".DEFAULT_ACTION);
         } else {
             $data["chyba"] = "<b>Hups! </b> Prihlaseni se nezdarilo. <br>Spatne uzivatelske jmeno/heslo";
	     $data["chyba_type"] = "danger";
         }
           
       } else {
        if(@$_GET["reg"] == "ok") {
            $data["chyba"] = "<b>Parada! </b> Uzivatel uspesne zaregistrovan, prihlaste se! ";
	    $data["chyba_type"] = "success";
	}
        if(@$_GET["logged"] == "no") {
	     $data["chyba"] = "Prosim nejprve se prihlaste";
	     $data["chyba_type"] = "info";
	     
	}
       }
       $this->view("prihlaseni",@$data);

    }

    public function odhlasit() {
        session_destroy();
        $this->redirect();
    }
    
    public function seznam() {
	$this->checkAdmin();
	

	 $data["uzivatele"] = $this->uzivatele_model->getSeznam();
	 $this->view("uzivatele", @$data);
    }
    
    public function smazat() {
	$this->checkAdmin();
	$id = $_GET["id"];

	 $this->uzivatele_model->smazat($id);
	 $this->flashMessage("success", "Uživatel úspěšně smazán");
	$this->redirect("uzivatele/seznam");
    }
	
    public function editUzivatel() {
	
	if($_GET["id_uzivatel"] == $_SESSION["uzivatel"]["id_uzivatel"]) {
		//ok - edit self
		$error = $this->uzivatele_model->editUzivatel($_GET);
		$redir_url = "zapujcky/seznam";
		$_SESSION["uzivatel"]["jmeno"] = $_GET["jmeno"];
		$_SESSION["uzivatel"]["prijmeni"] = $_GET["prijmeni"];
	} elseif ($this->checkAdmin(false)) {
		// ok - edit admin
		$error = $this->uzivatele_model->editUzivatel($_GET);
		$redir_url = "uzivatele/seznam";
	} else {
		$this->checkAdmin(true);
	}
	
	
	switch($error) {
		case "new":
			$this->flashMessage("success", "Uživatel úspěšně vytvořen");
			break;
		case "pass":
			$this->flashMessage("danger", "Zadaná hesla nesouhlasí - Heslo nezměněno");
			break;
		case "login":
			$this->flashMessage("danger", "Login již existuje, prosím zvolte jiný - neuloženo");
			break;
		case "new_pass_match":
			$this->flashMessage("danger", "Zadaná hesla nesouhlasí - Opakujte zadání");
			break;
		case "new_pass_exist":
			$this->flashMessage("danger", "Nevyplněno heslo");
			break;
		case false:
			$this->flashMessage("success", "Uživatel úspěšně upraven");
			break;
	} 
	$this->redirect($redir_url);
	
    }
    public function ajax() {
	$id = $_GET["id"];
	
	if ($id != $_SESSION["uzivatel"]["id_uzivatel"])
		$this->checkAdmin();

	$data = $this->uzivatele_model->getDetailUzivatele($id);
	print json_encode($data);
    }
    

   
}
