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
class Vydavatelstvi_Model extends Model{
    

    public function getSeznam() {
	$userData = dibi::query("SELECT * FROM [vydavatelstvi];")->fetchAll();
	return $userData;
    }
    
    public function smazat($id) {
	dibi::query("DELETE FROM [vydavatelstvi] WHERE id_vydavatelstvi = %i", $id);
    }
    
    public function getDetailVydavatelstvi($id) {
	$data = dibi::query("SELECT id_vydavatelstvi, nazev FROM [vydavatelstvi] WHERE id_vydavatelstvi = %i", $id)->fetch();
	return $data;
    }
    
    public function editVydavatelstvi($editData) {
	
	$saveData = array();
	$error = false;
	
	
	$saveData["nazev"] = $editData["nazev"];


	
	if (empty($editData["id_vydavatelstvi"])) {
	    
		dibi::query("INSERT INTO [vydavatelstvi]", $saveData);

		$error = "new";
	  

	} else {
	    dibi::query("UPDATE [vydavatelstvi] SET ", $saveData, "WHERE id_vydavatelstvi = %i", $editData["id_vydavatelstvi"]);
	     
	}
	return $error;

    }
    
    
}