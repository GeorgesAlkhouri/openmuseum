<?php

class HeaderController implements IController {

    public function display() {
        $view = new View();
        $view->setTemplate('header');
        $view->assign('title', 'openmuseum');

        return $view->loadTemplate();
    }

}

?>
