<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Zapujcky_Model
 *
 * @author Paja
 */
class Zapujcky_Model extends Model{
    public function getSeznam() {
        $id_uzivatele = $_SESSION["uzivatel"]["id_uzivatel"];
	$is_admin = $_SESSION["uzivatel"]["is_admin"];
	if ($is_admin) {
	    $zapujcky = dibi::query("SELECT id_kniha, uzivatel_id_uzivatel AS user_id, u.jmeno AS jmeno, u.prijmeni AS prijmeni, u.login AS login, knihy.nazev AS nazev_knihy, vydani, ISBN, rok, zanr.nazev AS zanr, datetime_rezervace, datetime_vypujceni, vypujceno 
				 FROM [uzivatel_has_knihy] 
				 LEFT JOIN [knihy] ON [uzivatel_has_knihy].[knihy_id_kniha] = [knihy].[id_kniha] 
				 LEFT JOIN [zanr] ON [knihy].[zanr_id] = [zanr].[id_zanr] 
				 LEFT JOIN [uzivatel] u ON [uzivatel_has_knihy].[uzivatel_id_uzivatel] = [u].[id_uzivatel] 
				 
				 ORDER BY datetime_rezervace DESC")->fetchAll(); 
	} else {
	    
	    $zapujcky = dibi::query("SELECT id_kniha, knihy.nazev AS nazev_knihy, vydani, ISBN, rok, zanr.nazev AS zanr, datetime_rezervace, datetime_vypujceni, vypujceno 
				 FROM [uzivatel_has_knihy] 
				 LEFT JOIN [knihy] ON [uzivatel_has_knihy].[knihy_id_kniha] = [knihy].[id_kniha] 
				 LEFT JOIN [zanr] ON [knihy].[zanr_id] = [zanr].[id_zanr] 
				 WHERE [uzivatel_has_knihy].[uzivatel_id_uzivatel] = %i 
				 ORDER BY datetime_rezervace DESC", $id_uzivatele)->fetchAll();
	}
	//var_dump($zapujcky);
	return $zapujcky;
        
    }
    
    public function smazat($id, $id_user){
		
		if ($_SESSION["uzivatel"]["is_admin"])
			$user_id = $id_user;
		else
			$user_id = $_SESSION["uzivatel"]["id_uzivatel"];
	
	//Check status 
	$status = dibi::query("SELECT vypujceno FROM [uzivatel_has_knihy] WHERE [uzivatel_id_uzivatel] = %i", $user_id, "AND [knihy_id_kniha] = %i", $id)->fetchSingle();
	
	if ($status != 1 && $status !== null) {
	     dibi::query("DELETE FROM [uzivatel_has_knihy] WHERE [uzivatel_id_uzivatel] = %i", $user_id, "AND [knihy_id_kniha] = %i", $id);
	     if ($status == 0) {
		 dibi::query("UPDATE [knihy] SET pocet_na_sklade = pocet_na_sklade+1 WHERE id_kniha = %i", $id);
	     }
	}
	else
	    return "Nelze smazat vypůjčenou knihu";
    }
    
    public function changeStatus($data) {
	
	$currentStatus = dibi::query("SELECT vypujceno FROM [uzivatel_has_knihy] WHERE [uzivatel_id_uzivatel] = %i", $data["id_user"], "AND [knihy_id_kniha] = %i", $data["id_kniha"])->fetch();
	
	if ($currentStatus !== false) {
	    $updateData = array(
		"vypujceno" => $data["status"],
		"datetime_vypujceni%sql" => "NOW()",
	    );
	
	    dibi::query("UPDATE [uzivatel_has_knihy] SET", $updateData, "WHERE [uzivatel_id_uzivatel] = %i", $data["id_user"], "AND [knihy_id_kniha] = %i", $data["id_kniha"]);
	}
	

	if ($currentStatus["vypujceno"] == 0 && $data["status"] == 2) {
	    dibi::query("UPDATE [knihy] SET pocet_na_sklade = pocet_na_sklade+1 WHERE id_kniha = %i", $data["id_kniha"]);
	}
	if ($currentStatus["vypujceno"] == 1 && $data["status"] == 2) {
	    dibi::query("UPDATE [knihy] SET pocet_na_sklade = pocet_na_sklade+1 WHERE id_kniha = %i", $data["id_kniha"]);
	}
	if ($currentStatus["vypujceno"] == 2 && ($data["status"] == 0 || $data["status"] == 1)) {
	    dibi::query("UPDATE [knihy] SET pocet_na_sklade = pocet_na_sklade-1 WHERE id_kniha = %i", $data["id_kniha"]);
	}
	$text = array();
	switch ($data["status"]) {
	    case 0:
		$text["color"] = "orange";

		$text["datetime"] = "";
		break;
	    case 1:
		$text["color"] = "red";

		$text["datetime"] = date("d.m.Y H:i:s");
		break;
	    case 2:
		$text["color"] = "green";

		$text["datetime"] = date("d.m.Y H:i:s");
		break;

	}
	
	return $text;
    
   }
}
