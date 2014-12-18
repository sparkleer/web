<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Kontroluje, zda je nastavena kdyz se chce uzivatel prihlasit.
 *
 * @author Paja
 */
class PController extends Controller{
      public function __construct() {
        parent::__construct(); 
        $this->check_session_exists();
    }
}
