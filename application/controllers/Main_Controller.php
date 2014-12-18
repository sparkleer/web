<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Main_Controller
 *
 * @author Paja
 */
class Main_Controller extends PController{
  
    public function index() {
       
                $this->redirect("zapujcky/seznam");
             
          
    }
    
    public function dump() {
        echo("<pre>"); var_dump($_SESSION); echo("</pre>");
    }
    
    
}
