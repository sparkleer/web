<?php 

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Uzivatele_Model
 *
 * @author Paja
 */
class Autori_Model extends Model{
    

    public function getSeznam() {
	$userData = dibi::query("SELECT * FROM [autor] ORDER BY prijmeni ASC;")->fetchAll();
	return $userData;
    }
    
    public function smazat($id) {
	dibi::query("DELETE FROM [autor] WHERE id_autor = %i", $id);
    }
    
    public function getDetailAutora($id) {
	$data = dibi::query("SELECT id_autor, jmeno, prijmeni FROM [autor] WHERE id_autor = %i", $id)->fetch();
	return $data;
    }
    
    public function editAutor($editData) {
	
	$saveData = array();
	$error = false;
	
	
	$saveData["jmeno"] = $editData["jmeno"];
	$saveData["prijmeni"] = $editData["prijmeni"];

	
	if (empty($editData["id_autor"])) {
	    
		dibi::query("INSERT INTO [autor]", $saveData);

		$error = "new";
	  

	} else {
	    dibi::query("UPDATE [autor] SET ", $saveData, "WHERE id_autor = %i", $editData["id_autor"]);
	     
	}
	return $error;

    }
    
    
}