<?php

class NavigationController implements IController {

	private $active = '';

	/**
	 * Konstruktor, erstellet den Controller.
	 *
	 * @param Array $request Array aus $_GET & $_POST.
	 */
	public function __construct($request){
		$this->active = !empty($request['view']) ? $request['view'] : 'search';
	}

    public function display() {
        $view = new View();
        $view->setTemplate('navBar');
        $view->assign('active', $this->active);

        return $view->loadTemplate();
    }

}

?>
