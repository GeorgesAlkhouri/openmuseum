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

    	$name = $this->prepareInput($request["name"]);
    	$artist = $this->prepareInput($request["artist"]);
    	$museum = $this->prepareInput($request["museum"]);
    	$owner = $this->prepareInput($request["owner"]);
    	$description = $this->prepareInput($request["description"]);
    	$keywords = $this->prepareKeywords($request["keywords"]);

    	if (!$this->validateText($name) ||
    		!$this->validateText($artist) ||
    		!$this->validateText($museum) ||
    		!$this->validateText($owner) ||
    		!$this->validateText($description)) {

    		echo "Wrong text input (Artist, Name, etc...)";
    		return;
    	}

    	if (count($keywords) == 0) {

    		echo "No keywords";
    		return;
    	}

    	if (!isset($request["category"])) {

    		echo "No category";
    		return;
    	}

    	$categories = $request["category"];

    	if (!$this->validateFile($request["picture"])) {

    		echo "Wrong image type";
    		return;
    	} 
    }

    private function prepareInput($data) {

 		$data = trim($data);
  		$data = stripslashes($data);
  		$data = htmlspecialchars($data);
  		return $data;
	}

    private function prepareKeywords($data) {

		$keywords = explode(",", $data);
		
		//removes all empty elements
		$keywords = array_filter($keywords);

		$keywords = array_map("trim", $keywords);
		$keywords = array_map("stripslashes", $keywords);
		$keywords = array_map("htmlspecialchars", $keywords);

		return $keywords;
    }

    private function validateText($text) {

    	return strlen($text) > 0;
    }

    private function validateFile($data) {

    	$allowedFileTypes = array("image/jpeg", "image/tiff", "image/png");
    	$uploadedFileType = $data["type"];

    	return in_array($uploadedFileType, $allowedFileTypes);
    }

}

?>
