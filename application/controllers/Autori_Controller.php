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
class Autori_Controller extends PController{
    
    public function __construct(){
	parent::__construct();
	$this->load_model("autori");
    }
    
       
    public function seznam() {
	$this->checkAdmin();
	

	 $data["autori"] = $this->autori_model->getSeznam();
	 $this->view("autori", @$data);
    }
    
    public function smazat() {
	$this->checkAdmin();
	$id = $_GET["id"];

	 $this->autori_model->smazat($id);
	 $this->flashMessage("success", "Autor úspěšně smazán");
	$this->redirect("autori/seznam");
    }
    public function editAutor() {
	$this->checkAdmin();
	$error = $this->autori_model->editAutor($_GET);
	
	if ($error == false)
	    $this->flashMessage("success", "Autor úspěšně upraven");
	 else if ($error = "new") {
	    $this->flashMessage("success", "Autor úspěšně vytvořen");
	}
	$this->redirect("autori/seznam");
	
    }
    public function ajax() {
	$this->checkAdmin();
	$id = $_GET["id"];

	$data = $this->autori_model->getDetailAutora($id);
	print json_encode($data);
    }
    

   
}
