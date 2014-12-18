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
class Zapujcky_Controller extends PController{
    
    public function __construct(){
	parent::__construct();
	$this->load_model("zapujcky");
    }
    
    public function seznam() {
        $seznam = $this->zapujcky_model->getSeznam();
        $data["seznam"] = $seznam;
	
        $this->view("zapujcky", $data);
    }
    
    public function smazat() {
	$this->zapujcky_model->smazat($_GET["id"], $_GET["user_id"]);
	$this->redirect("zapujcky/seznam");
    }
    
    public function changeStatus() {
	$this->checkAdmin();
	$json = $this->zapujcky_model->changeStatus($_GET);

	echo json_encode($json);
    }
}
