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
class Knihy_Model extends Model{
    
    public function getSeznam($hledat = NULL) {
		$user_id = $_SESSION["uzivatel"]["id_uzivatel"];
		//Search
		if (isset($hledat)) {
			
			
			$search_arr = array();
			foreach($hledat as $column => $value) {
				if (trim($value) != "") {
					if ($column == "zanr.id_zanr") {
						$search_arr[$column] = $value;
					}
					else
						$search_arr[$column."%~like~"] = $value;
				}
			}
			
			
			$seznam = dibi::query("SELECT id_kniha, knihy.nazev AS nazev_knihy, vydani, ISBN, rok, zanr.nazev AS zanr, pocet_na_sklade AS pocet, uk.vypujceno, v.nazev AS vydavatelstvi
							FROM [knihy] 
								LEFT JOIN [zanr] ON [knihy].[zanr_id] = [zanr].[id_zanr]
								LEFT JOIN [vydavatelstvi] v ON [knihy].[vydavatelstvi_id_vydavatelstvi] = [v].[id_vydavatelstvi]
								LEFT JOIN [uzivatel_has_knihy] uk ON [knihy].[id_kniha] = [uk].[knihy_id_kniha] AND 
																  [uk].[uzivatel_id_uzivatel] = %i",$user_id,
								"INNER JOIN [knihy_has_autor] ka ON [knihy].[id_kniha] = [ka].[knihy_id_kniha]
								 LEFT JOIN [autor] ON [ka].[autor_id_autor] = [id_autor]".
							" WHERE %and" , $search_arr, " GROUP BY id_kniha ORDER BY knihy.nazev ASC")->fetchAll();
			
		}
		else {
			$seznam = dibi::query("SELECT id_kniha, knihy.nazev AS nazev_knihy, vydani, ISBN, rok, zanr.nazev AS zanr, pocet_na_sklade AS pocet, uk.vypujceno, v.nazev AS vydavatelstvi 
							FROM [knihy] 
								LEFT JOIN [zanr] ON [knihy].[zanr_id] = [zanr].[id_zanr] 
								LEFT JOIN [vydavatelstvi] v ON [knihy].[vydavatelstvi_id_vydavatelstvi] = [v].[id_vydavatelstvi]
								LEFT JOIN [uzivatel_has_knihy] uk ON [knihy].[id_kniha] = [uk].[knihy_id_kniha] AND 
																  [uk].[uzivatel_id_uzivatel] = %i",$user_id,
							"ORDER BY knihy.nazev ASC")->fetchAll();
		}
       
	
	
	
	//Autoři
	foreach ($seznam as $key=>$kniha) {
	    $autori = dibi::query("SELECT [autor].[jmeno] AS jmeno, [autor].[prijmeni] AS prijmeni FROM [knihy_has_autor] LEFT JOIN [autor] ON [knihy_has_autor].[autor_id_autor] = [autor].[id_autor] WHERE [knihy_has_autor].[knihy_id_kniha] = %i",$kniha["id_kniha"])->fetchAll();
	    
	    if (!empty($autori)) {
	    $jmeno_array = explode(" ", $autori[0]["jmeno"]);
	    if (count($jmeno_array) > 0) {
		$short_jmeno = "";
		foreach ($jmeno_array as $jmeno) {
		    $short_jmeno .= substr($jmeno, 0, 1) . ". ";
		}
	    }
	    else $short_jmeno = $autori[0]["jmeno"] . " ";
		
	    $seznam[$key]["main_autor"] = $short_jmeno . $autori[0]["prijmeni"];
	    $seznam[$key]["autori"] = $autori;
	}
	}
	//Zanry
	$zanry = dibi::query("SELECT * FROM zanr;")->fetchAll();
	
	
	return array("seznam" => $seznam, "zanry"=>$zanry);
        
    }
    
    public function smazat($id){

        dibi::query("DELETE FROM [knihy] WHERE [id_kniha] = %i", $id);
    }
    
    public function rezervovat($id) {
	$user_id = $_SESSION["uzivatel"]["id_uzivatel"];
	// Check if book is available
	$pocet = dibi::query("SELECT count(*) AS pocet FROM [knihy] WHERE [id_kniha] = %i",$id)->fetchSingle();
	
	if ($pocet == 0) {
	    return "Kniha již bohužel není dostupná.";
	}
	
	//Check if book is already borrowed
	$borrowed = dibi::query("SELECT vypujceno FROM [uzivatel_has_knihy] WHERE [knihy_id_kniha] = %i", $id , "AND [uzivatel_id_uzivatel] = %i", $user_id)->fetch();
	
	if ($borrowed === false) {
	    $data = array(
	    'uzivatel_id_uzivatel' => $_SESSION["uzivatel"]["id_uzivatel"],
	    'knihy_id_kniha' => $id,
	    'datetime_rezervace' => date("Y-m-d H:i:s")
	    );
	    dibi::query("INSERT INTO [uzivatel_has_knihy]", $data);

	    //Update pocet
	    dibi::query("UPDATE [knihy] SET pocet_na_sklade = pocet_na_sklade-1 WHERE id_kniha = %i", $id);
	}
	else if ($borrowed["vypujceno"] == 2){
	    
	    $update = array(
		"datetime_rezervace" => date("Y-m-d H:i:s"),
		"datetime_vypujceni" => NULL,
		"vypujceno" => 0
	    );
	    dibi::query("UPDATE [uzivatel_has_knihy] SET ", $update, "WHERE uzivatel_id_uzivatel = %i", $user_id, "AND knihy_id_kniha = %i", $id);
	    //Update pocet
	    dibi::query("UPDATE [knihy] SET pocet_na_sklade = pocet_na_sklade-1 WHERE id_kniha = %i", $id);
	}
	else {
	    return "Rezervovanou/půjčenou knihu nelze znovu rezervovat";
	}
	
	
	
	
	

    }
    
    public function getDetailKnihy($id) {
	if ($id != -1) {
	$data = dibi::query("SELECT knihy.nazev AS nazev_knihy, rok, vydavatelstvi_id_vydavatelstvi AS vydavatelstvi , ISBN AS isbn, vydani, pocet_na_sklade AS pocet, zanr_id as zanr, jazyk
				FROM [knihy] 
			     WHERE id_kniha = %i", $id)->fetch();
	}
	$data["autori"] = dibi::query("SELECT id_autor, jmeno, prijmeni, ka.knihy_id_kniha AS selected FROM autor LEFT JOIN [knihy_has_autor] ka ON ka.autor_id_autor = [autor].[id_autor] AND ka.knihy_id_kniha = %i",$id, "ORDER BY [ka.knihy_id_kniha] DESC, [prijmeni] ASC")->fetchAll();
	$data["zanry"] = dibi::query("SELECT * FROM [zanr] ORDER BY [nazev] ASC")->fetchAll();
	$data["vydavatelstvi"] = dibi::query("SELECT * FROM [vydavatelstvi] ORDER BY [nazev] ASC")->fetchAll();
	return $data;
    }
    
        public function editKniha($editData) {
	
	$saveData = array();
	$error = false;
	
	$id_kniha = null;
	$saveData["nazev"] = $editData["nazev_knihy"];
	$saveData["pocet_na_sklade"] = $editData["pocet_na_sklade"];
	$saveData["vydani"] = $editData["vydani"];
	$saveData["ISBN"] = $editData["isbn"];
	$saveData["rok"] = $editData["rok"];
	$saveData["zanr_id"] = $editData["zanr"];
	$saveData["vydavatelstvi_id_vydavatelstvi"] = $editData["vydavatelstvi"];
	
	if (empty($editData["id_kniha"])) {
	    
		dibi::query("INSERT INTO [knihy]", $saveData);
		$id_kniha = dibi::getInsertId();

		$error = "new";
	  

	} else {
	    dibi::query("UPDATE [knihy] SET ", $saveData, "WHERE id_kniha = %i", $editData["id_kniha"]);
	    dibi::query("DELETE FROM [knihy_has_autor] WHERE knihy_id_kniha = %i", $editData["id_kniha"]);
	    $id_kniha = $editData["id_kniha"];
	      
	}
	
	//Zpracovani autoru
	if (isset($editData["autor"])) {
	    foreach($editData["autor"] as $autor_id) {
		$autorData = array("knihy_id_kniha" => $id_kniha,"autor_id_autor" => $autor_id);
		dibi::query("INSERT INTO [knihy_has_autor]", $autorData);
	    }
	}
	return $error;

    }
}
