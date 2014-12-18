<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of View
 *
 * @author Paja
 */

require(ROOT.DS."libs".DS."Twig".DS."Autoloader.php");
class View {
    public $vars;
    
    public function __construct($template_name, $vars) {
        $template_name = $template_name . ".twig";
        Twig_Autoloader::register();
        $template_dir = array(TEMPLATE_DIR, TEMPLATE_DIR.DS."base");
        $loader = new Twig_Loader_Filesystem($template_dir);
        $twig = new Twig_Environment($loader);
       
        
        
        if (isset($vars)) {
             //Add global variables
            $vars = array_merge($vars,$this->getGlobalVars());
            $twig->display($template_name, $vars);        
        }
        else {
            $twig->display($template_name, $this->getGlobalVars());        
        }
            
        
    }
    
    public function getGlobalVars() {
        $globVars["link"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $globVars["session"] = $_SESSION;
	
	//JS & CSS var
	if (isset($_GET["url"])) {
	    $url = $_GET["url"];
	    $url_array = explode("/",$url);
	    $globVars["page_js"] = $url_array[0];
	}

	//FlashMessage
	if (isset($_SESSION["flashMessage"])) {
	    $globVars["flashMessage"] = $_SESSION["flashMessage"];
	    unset($_SESSION["flashMessage"]);
	}
	
        return $globVars;
    }
    
  
    

}
