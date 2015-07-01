<?php

class BodyController implements IController {

    private $body = "";


    public function __construct($request){
        $this->body = !empty($request["view"]) ? $request["view"] : "search";

        // check for upload request
        if (isset($request["action"]) && strcmp($request["action"], "upload") == 0) {

        	$this->upload($request);
        } else if (isset($request["action"]) && strcmp($request["action"], "search") == 0) {

            $this->search($request);
        } elseif (isset($request["action"]) && strcmp($request["action"], "picture_search") == 0) {
        	
        	$this->pictureSearch($request);
        }
    }


    public function display() {
        $view = new View();
        $view->setTemplate($this->body);
        
        return $view->loadTemplate();
    }

    public function pictureSearch($request) {

    	if (is_uploaded_file($request["picture_comparing"]["tmp_name"])) {

            if (!$this->validateFile($request["picture_comparing"])) {

                echo "Wrong image type or image size to big.";
                return;
            }
        }

        if (is_uploaded_file($request["texture_comparing"]["tmp_name"])) {

            if ( isset($request["texture_comparing"]) & !$this->validateFile($request["texture_comparing"])) {

                echo "Wrong image type or image size to big.";
                return;
            }
        }
    }

    public function search($request) {

        $searchAll = $this->prepareInput($request["search_all"]);
        $pictureName = $this->prepareInput($request["search_picture_name"]);
        $artist = $this->prepareInput($request["search_picture_artist"]);
        $museum = $this->prepareInput($request["search_picture_museum"]);
        $owner = $this->prepareInput($request["search_picture_owner"]);
        $keywords = $this->prepareKeywords($request["search_picture_keywords"]);
        $description = $this->prepareInput($request["search_picture_decription"]);
        $categories = $this->mapCategories($request["search_category"]);

        //TODO: Finish search data validation --> do search

    }

    public function upload($request) {

    	$pictureName = $this->prepareInput($request["picture_name"]);
    	$pictureCreationDate = $this->prepareInputDate($request["picture_creation_date"]);
    	$description = $this->prepareInput($request["description"]);
    	$keywords = $this->prepareKeywords($request["keywords"]);
        $categories = NULL;

    	if (isset($request["category"]))
			$categories = $this->mapCategories($request["category"]);

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

    	$isMuseumOwner = NULL;

        $isMuseumExhibitor = NULL;
        $museumName = NULL;
        $museumAdress = NULL;
        $museumWebsite = NULL;

    	// Museum is optional
    	if (strlen($this->prepareInput($request["museum_name"])) > 0) {
    		$museumName = $this->prepareInput($request["museum_name"]);
    		$museumAdress = $this->prepareInput($request["museum_adress"]);
    		$museumWebsite = $this->prepareInput($request["museum_website"]);

    		if (isset($request["museum_isOwner"]))
    			$isMuseumOwner = true;
    		else 
    			$isMuseumOwner = false;

    		if (isset($request["museum_isExhibitor"]))
    			$isMuseumExhibitor = true;
    		else 
    			$isMuseumExhibitor = false;

	    	if (!$this->validateText($museumName) ||
	    		!$this->validateText($museumAdress) ||
	    		!$this->validateText($museumWebsite)) {

	    		echo "Wrong museum input";
	    	}
    	}

        $ownerFirstname = NULL;
        $ownerSurename = NULL;

        // Check owner if museum is not declared or museum is not owner
    	if (!$isMuseumOwner || strlen($this->prepareInput($request["museum_name"])) <= 0) {

            if (!isset($request["owner_firstname"]) || 
                !isset($request["owner_surname"])) {
                echo "Wrong owner input";
                return;
            }

    		$ownerFirstname = $this->prepareInput($request["owner_firstname"]);
    		$ownerSurename = $this->prepareInput($request["owner_surname"]);

    		if (!$this->validateText($ownerFirstname) ||
    			!$this->validateText($ownerSurename)) {

    				echo "Wrong owner input";
                    return;
    		}
    	} else {

            $ownerFirstname = $museumName;
            $ownerSurename = $museumName;

        }

    	if (!$this->validateFile($request["picture"])) {

    		echo "Wrong image type or image size to big.";
    		return;
    	} 

        $picture = new Picture();
        $picture->name = $pictureName;
        $picture->description = $description;
        $picture->creation_date = $pictureCreationDate;

        date_default_timezone_set("Europe/Berlin");
        $currentDate = date("j.m.Y");

        $picture->upload_date = $currentDate;
        $picture->image_name = basename($request["picture"]["tmp_name"]);
        $picture->image_path = dirname($request["picture"]["tmp_name"]) . "/";
        $picture->artist_safety_level = 100;

        $artist = new Artist();
        $artist->firstname = $artistFirstname;
        $artist->lastname = $artistSurname;
        $artist->birth_date = $artistBirthday;
        $artist->death_date = $artistDeathday;

        $museum = new Museum();
        $museum->name = $museumName;
        $museum->adress = $museumAdress;
        $museum->website = $museumWebsite;
        $museum->isExhibitor = $isMuseumExhibitor;
        $museum->isOwner = $isMuseumOwner;

        $owner = new Owner();
        $owner->firstname = $ownerFirstname;
        $owner->lastname = $ownerSurename;

        // echo " Picture ".$picture . "<br>" ; 
        // echo " Owner ". $owner . "<br>" ; 
        // echo "  Museum ". $museum . "<br>" ;
        // echo " Artist " . $artist . "<br>" ;
        // echo " Categories " . print_r($categories);
        
        DbManager::Instance()->insertUserDataInDb($picture, $artist, $museum, $owner, $keywords, $categories);
    }

    private function mapCategories($categories) {

        array_walk($categories, function(&$key, $value) {

            $category = new Category();
            $category->id = $value + 1;
            $category->title = $key;

            $key = $category;
        });

        return $categories;
    }

    private function prepareInput($data) {

 		$data = trim($data);
  		$data = stripslashes($data);
  		$data = htmlspecialchars($data);
  		return $data;
	}

	private function prepareInputDate($date) {

		$date = $this->prepareInput($date);

        $dateTime = DateTime::createFromFormat("j.m.Y", $date);

        if ($dateTime)
            return $dateTime->format("j.m.Y");
        else
            return false;
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
