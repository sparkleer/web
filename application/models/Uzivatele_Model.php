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
class Uzivatele_Model extends Model{
    
    public function getJmeno() {
        return "Paja";
    }
    
    public function prihlaseni() {
        
    }

    public function validace_registrace($udaje) {
        if(trim($udaje["jmeno"]) == "")
            return "<b>ERROR:</b> Nebylo zadano jmeno";
        if(trim($udaje["prijmeni"]) == "")
            return "<b>ERROR:</b> Nebylo zadano prijmeni";
        if(trim($udaje["login"]) == "")
            return "<b>ERROR:</b> Nebyl zadan login";
        if(trim($udaje["heslo"]) == "")
            return "<b>ERROR:</b> Nebylo zadano heslo";
	if (trim($udaje["heslo"]) != trim($udaje["heslo1"]))
	    return "<b>ERROR:</b> Zadana hesla nesouhlasi";
       
        
        if($this->checkLoginExists($udaje["login"])) {
            return "<b>ERROR:</b> Uzivatel jiz existuje!";
        }
        
        return TRUE;
    }
    
    public function checkLoginExists($login) {
	//$where[] = array("column" => "login",
	//				 "symbol" => "=",
	//				 "value" => $login);
		//$exists = $this->db->DBSelectOne("uzivatel", "count(*) AS pocet", $where);
		$exists = dibi::query("SELECT count(*) FROM [uzivatel] WHERE login = %s", $login)->fetchSingle();
		if($exists) {
			return true;
		} else {
			return false;
		}
	}
    
    public function zaregistruj($udaje) {
        unset($udaje["registrace"]);
	unset($udaje["heslo1"]);
        $udaje["heslo"] = sha1($udaje["heslo"]);
        dibi::insert("uzivatel", $udaje)->execute();
        
        
    }
    
    /**
     * 
     * @param type $udaje
     * @return boolean
     */
    public function prihlasit($udaje) {
        //$where[] = array("column" => "login",
	//				 "symbol" => "=",
	//				 "value" => $udaje["mail_odesilatele"]);
        //$loginData = $this->db->DBSelectOne("uzivatel", "*", $where);
	
	$loginData = dibi::query("SELECT * FROM [uzivatel] WHERE login = %s", $udaje["mail_odesilatele"])->fetch();
        
        if(!$loginData) {
            return false;
        }
        
        if(sha1($udaje["heslo"]) != $loginData["heslo"]) {
            return false;
        } 
        
	$loginData["ip"] = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
        $_SESSION["uzivatel"] = (array) $loginData;
      
        return true;
    }
    
    public function getSeznam() {
	$userData = dibi::query("SELECT * FROM [uzivatel] WHERE is_admin != 1")->fetchAll();
	return $userData;
    }
    
    public function smazat($id) {
	dibi::query("DELETE FROM [uzivatel] WHERE id_uzivatel = %i", $id);
    }
    
    public function getDetailUzivatele($id) {
	$data = dibi::query("SELECT id_uzivatel, jmeno, prijmeni, login FROM [uzivatel] WHERE id_uzivatel = %i", $id)->fetch();
	return $data;
    }
    
    public function editUzivatel($editData) {
	
	$saveData = array();
	$error = false;
	if (!empty($editData["heslo"])) {
	    if ($editData["heslo"] == $editData["heslo1"]) {
		$saveData["heslo"]  = sha1($editData["heslo"]);
	    } else {
		$error = "pass";
	    }

	}
	// Check login
	
	if ($this->checkLoginExists($editData["login"])) {
		return "login";
	}
	$saveData["jmeno"] = $editData["jmeno"];
	$saveData["prijmeni"] = $editData["prijmeni"];
	$saveData["login"] = $editData["login"];
	
	if (empty($editData["id_uzivatel"])) { // Creating new user
	    if (!empty($editData["heslo"]))
		{
			if ($error != "pass") 
				dibi::query("INSERT INTO [uzivatel]", $saveData);
			else
				return "new_pass_match";
			
			return "new";
	    } else return "new_pass_exist";

	} else { // Updating existing user
	    dibi::query("UPDATE [uzivatel] SET ", $saveData, "WHERE id_uzivatel = %i", $editData["id_uzivatel"]);
	    return $error;  
	}

    }
    
    
}