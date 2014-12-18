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
class Zanry_Controller extends PController{
    
    public function __construct(){
	parent::__construct();
	$this->load_model("zanry");
    }
    
       
    public function seznam() {
	$this->checkAdmin();
	

	 $data["zanry"] = $this->zanry_model->getSeznam();
	 $this->view("zanry", @$data);
    }
    
    public function smazat() {
	$this->checkAdmin();
	$id = $_GET["id"];

	 $this->zanry_model->smazat($id);
	 $this->flashMessage("success", "Žánr úspěšně smazán");
	$this->redirect("zanry/seznam");
    }
    public function editZanr() {
	$this->checkAdmin();
	$error = $this->zanry_model->editZanr($_GET);
	
	if ($error == false)
	    $this->flashMessage("success", "Žánr úspěšně upraven");
	 else if ($error = "new") {
	    $this->flashMessage("success", "Žánr úspěšně vytvořen");
	}
	$this->redirect("zanry/seznam");
	
    }
    public function ajax() {
	$this->checkAdmin();
	$id = $_GET["id"];

	$data = $this->zanry_model->getDetailZanru($id);
	print json_encode($data);
    }
    

   
}
