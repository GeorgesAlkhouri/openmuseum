<?php

class BodyController implements IController {

    private $body = '';


    public function __construct($request){
        $this->body = !empty($request['view']) ? $request['view'] : 'search';
    }


    public function display() {
        $view = new View();
        $view->setTemplate($this->body);
        
        return $view->loadTemplate();
    }

}

?>
