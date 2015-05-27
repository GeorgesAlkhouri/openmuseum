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

    	$pictureName = $this->prepareInput($request["picture_name"]);
    	$pictureCreationDate = $this->prepareInputDate($request["picture_creation_date"]);
    	$description = $this->prepareInput($request["description"]);
    	$keywords = $this->prepareKeywords($request["keywords"]);

    	if (isset($request["category"]))
			$categories = $request["category"];

    	if (!$this->validateText($pictureName) ||
    		!$this->validateText($description) ||
    		!$pictureCreationDate) {

    		echo "Wrong picture input";
    		return;
    	}

    	if (count($keywords) == 0 || count($keywords) > 10) {

    		echo "Wrong keywords count";
    		return;
    	}

    	$artistFirstname = $this->prepareInput($request["artist_firstname"]);
    	$artistSurname = $this->prepareInput($request["artist_surname"]);
    	$artistBirthday = $this->prepareInputDate($request["artist_birthday"]);
    	$artistDeathday = $this->prepareInputDate($request["artist_deathday"]);

    	if (!$this->validateText($artistFirstname) ||
    		!$this->validateText($artistSurname) ||
    		!$artistBirthday ||
    		!$artistDeathday) {

    		echo "Wrong artist input";
    		return;
    	}

    	$isMuseumOwner = false;

    	// Museum is optional
    	if (strlen($this->prepareInput($request["museum_name"])) > 0) {
    		$museumName = $this->prepareInput($request["museum_name"]);
    		$museumAdress = $this->prepareInput($request["museum_adress"]);
    		$museumWebsite = $this->prepareInput($request["museum_website"]);

    		if (isset($request["museum_isExhibitor"]))
    			$isMuseumOwner = true;
    		else 
    			$isMuseumOwner = false;

    		if (isset($request["museum_isOwner"]))
    			$isMuseumExhibitor = true;
    		else 
    			$isMuseumExhibitor = false;

	    	if (!$this->validateText($museumName) ||
	    		!$this->validateText($museumAdress) ||
	    		!$this->validateText($museum_website)) {

	    		echo "Wrong museum input";
	    	}
    	}

    	if (!$isMuseumOwner) {

    		$ownerFirstname = $this->prepareInput($request["owner_firstname"]);
    		$ownerSurename = $this->prepareInput($request["owner_surname"]);

    		if (!$this->validateText($ownerFirstname) ||
    			!$this->validateText($ownerSurename)) {

    				echo "Wrong owner input";
    			}
    	}

    	if (!$this->validateFile($request["picture"])) {

    		echo "Wrong image type or image size to big.";
    		return;
    	} 

    }

    private function prepareInput($data) {

 		$data = trim($data);
  		$data = stripslashes($data);
  		$data = htmlspecialchars($data);
  		return $data;
	}

	private function prepareInputDate($date) {

		$date = $this->prepareInput($date);

		return DateTime::createFromFormat("j.m.Y", $date);
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

    	if ($data["size"] > 625000)
    		return false;

    	$allowedFileTypes = array("image/jpeg", "image/tiff", "image/png");
    	$uploadedFileType = $data["type"];

    	return in_array($uploadedFileType, $allowedFileTypes);
    }

}

?>
