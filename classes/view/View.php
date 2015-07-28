<?php

class View {

	private $path = 'templates';
	private $template = '';

	private $_ = array();

	public function assign($key, $value){
		$this->_[$key] = $value;
	}

	public function setTemplate($template){
		$this->template = $template;
	}

	public function loadTemplate(){

		$tpl = $this->template;

		$file = $this->path . DIRECTORY_SEPARATOR . $tpl . '.php';

		$exists = file_exists($file);

		if ($exists){

			ob_start();

			include $file;
			$output = ob_get_contents();
			ob_end_clean();

			return $output;
		}
		else {

			return 'could not find template';
		}
	}

}

?>
