<?php

function __autoload($class) {
   if (file_exists(ROOT.DS."application".DS."controllers".DS.$class.".php")) {
       require_once (ROOT.DS."application".DS."controllers".DS.$class.".php");
       
   } elseif (file_exists(ROOT.DS."application".DS."models".DS.$class.".php")) {
       require_once (ROOT.DS."application".DS."models".DS.$class.".php");
       
   } elseif (file_exists(ROOT.DS."core".DS.$class.".php")) {
       require_once (ROOT.DS."core".DS.$class.".php");
       
   } else {
       if (strpos($class,"Dibi") === FALSE)
	    die("ERROR: Module $class not found");
   }
   

}

function nacti() {
    global $url;
    if(!isset($url)) {
        $cName = DEFAULT_CONTROLLER; 
        $aName = DEFAULT_ACTION; 
        
    } else {
        $urlArray = explode("/", $url);
        $cName = $urlArray[0]; // controller
        
        $aName = isset($urlArray[1]) ? $urlArray[1] : DEFAULT_ACTION; // metoda controlleru
        
    }
    $parametr = isset($urlArray[2]) ? $urlArray[2] : NULL;
    $class = ucfirst($cName)."_Controller";
    if(class_exists($class) && (int)method_exists($class, $aName)) {
        $controller = new $class;
        $controller->$aName($parametr);
    } else {
        die("Controller '$class' nebo jeho metoda '$aName' neexistuji");
    }
}

nacti();






?>