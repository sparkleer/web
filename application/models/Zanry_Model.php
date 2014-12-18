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
class Zanry_Model extends Model{
    

    public function getSeznam() {
	$userData = dibi::query("SELECT * FROM [zanr] ORDER BY nazev ASC;")->fetchAll();
	return $userData;
    }
    
    public function smazat($id) {
	dibi::query("DELETE FROM [zanr] WHERE id_zanr = %i", $id);
    }
    
    public function getDetailZanru($id) {
	$data = dibi::query("SELECT id_zanr, nazev FROM [zanr] WHERE id_zanr = %i", $id)->fetch();
	return $data;
    }
    
    public function editZanr($editData) {
	
	$saveData = array();
	$error = false;
	
	
	$saveData["nazev"] = $editData["nazev"];


	
	if (empty($editData["id_zanr"])) {
	    
		dibi::query("INSERT INTO [zanr]", $saveData);

		$error = "new";
	  

	} else {
	    dibi::query("UPDATE [zanr] SET ", $saveData, "WHERE id_zanr = %i", $editData["id_zanr"]);
	     
	}
	return $error;

    }
    
    
}