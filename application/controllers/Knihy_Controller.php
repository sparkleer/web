<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Zapujcky_Controller
 *
 * @author Paja
 */
class Knihy_Controller extends PController{
    
    public function __construct(){
	parent::__construct();
	$this->load_model("knihy");
    }
    public function seznam() {
    
       $data = $this->knihy_model->getSeznam();
	   $this->view("knihy", @$data);
    }
	
	public function hledat() {
		$data = $this->knihy_model->getSeznam($_GET["search"]);
		
	    $this->view("knihy", @$data);
	}
    
    public function rezervovat() {
	$id_knihy = $_GET["id"];

        $error = $this->knihy_model->rezervovat($id_knihy);
	if ($error !== null)
	    $this->flashMessage("danger", $error);
    $this->redirect("zapujcky/seznam");
    }
    
    public function smazat() {
	$this->checkAdmin();
	$id_knihy = $_GET["id"];
	$this->knihy_model->smazat($_GET["id"]);
	$this->flashMessage("success", "Kniha smazána");
	$this->redirect("knihy/seznam");
    }
    
    public function editKniha() {
	$this->checkAdmin();
	$error = $this->knihy_model->editKniha($_GET);
	
	if ($error == false)
	    $this->flashMessage("success", "Kniha úspěšně upravena");
	else if ($error = "new") {
	    $this->flashMessage("success", "Kniha úspěšně vytvořena");
	}
	$this->redirect("knihy/seznam");
    }
    public function ajax() {
	$id = $_GET["id"];
	$data = $this->knihy_model->getDetailKnihy($id);
	print json_encode($data);
    }
	
	
}
