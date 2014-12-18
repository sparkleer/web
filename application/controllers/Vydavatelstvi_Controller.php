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
class Vydavatelstvi_Controller extends PController{
    
    public function __construct(){
	parent::__construct();
	$this->load_model("vydavatelstvi");
    }
    
       
    public function seznam() {
	$this->checkAdmin();
	

	 $data["vydavatelstvi"] = $this->vydavatelstvi_model->getSeznam();
	 $this->view("vydavatelstvi", @$data);
    }
    
    public function smazat() {
	$this->checkAdmin();
	$id = $_GET["id"];

	 $this->vydavatelstvi_model->smazat($id);
	 $this->flashMessage("success", "Vydavatelství úspěšně smazáno");
	$this->redirect("vydavatelstvi/seznam");
    }
    public function editVydavatelstvi() {
	$this->checkAdmin();
	$error = $this->vydavatelstvi_model->editVydavatelstvi($_GET);
	
	if ($error == false)
	    $this->flashMessage("success", "Vydavatelství úspěšně upraveno");
	 else if ($error = "new") {
	    $this->flashMessage("success", "Vydavatelství úspěšně vytvořeno");
	}
	$this->redirect("vydavatelstvi/seznam");
	
    }
    public function ajax() {
	$this->checkAdmin();
	$id = $_GET["id"];

	$data = $this->vydavatelstvi_model->getDetailVydavatelstvi($id);
	print json_encode($data);
    }
    

   
}
