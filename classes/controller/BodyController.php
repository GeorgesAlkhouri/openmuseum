<?php

class BodyController implements IController {

    private $body = "";


    public function __construct($request){
        $this->body = !empty($request["view"]) ? $request["view"] : "search";

        // check for upload request
        if (isset($request["action"]) && strcmp($request["action"], "upload") == 0) {

        	$this->upload($request);
        }
    }


    public function display() {
        $view = new View();
        $view->setTemplate($this->body);
        
        return $view->loadTemplate();
    }

    public function upload($request) {

    	
    }

}

?>
