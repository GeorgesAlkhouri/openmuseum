<?php

class BodyController implements IController {

    private $body = "";
    private $view;


    public function __construct($request){

        $this->view = new View();
        $this->body = !empty($request["view"]) ? $request["view"] : "search";

        // check for view assignment
        if ($this->body === "results") {

            $this->view->assign("results", $_SESSION['RESULTS']);
            unset($_SESSION['RESULTS']);
        } elseif ($this->body === "picture") {

          $this->view->assign("picture_data", $_SESSION["PICTURE_DATA"]);
          $this->view->assign("name", $request["name"]);
          $this->view->assign("mime_type", $request["mime_type"]);

          unset($_SESSION["PICTURE_DATA"]);
        }

        // check for upload request
        if (isset($request["action"]) && strcmp($request["action"], "upload") == 0) {

        	$this->upload($request);
        } else if (isset($request["action"]) && strcmp($request["action"], "search") == 0) {

            $this->search($request);
        } else if (isset($request["action"]) && strcmp($request["action"], "picture_search") == 0) {

        	$this->pictureSearch($request);
        }
    }


    public function display() {

        $this->view->setTemplate($this->body);

        return $this->view->loadTemplate();
    }

    public function pictureSearch($request) {

      $colorWeight = $request["color_weight"] / 100;
      $shapeWeight = $request["shape_weight"] / 100;
      $textureWeight = $request["texture_weight"] / 100;

      //Type: DisplayPicture
      $result;

    	if (isset($request["picture_comparing"]) && is_uploaded_file($request["picture_comparing"]["tmp_name"])) {

            if (!$this->validateFile($request["picture_comparing"])) {

                echo "Wrong image type or image size to big.";
                return;
            }

            $comparisonPicture = new ComparisonPicture();

            $comparisonPicture->weightColor = $colorWeight;
            $comparisonPicture->weightTexture = $textureWeight;
            $comparisonPicture->weightShape = $shapeWeight;
            $comparisonPicture->weightLocation = 0.0;
            $comparisonPicture->threshold = 60;
            $comparisonPicture->image_path = $request["picture_comparing"]["tmp_name"];
            $comparisonPicture->image_name = $request["picture_comparing"]["name"];

            $result = DbManager::Instance()->compare($comparisonPicture);
            $_SESSION['RESULTS'] = $result;

            $this->reroute('index.php?view=results');
        } else if ( isset($request["picture_color"]) && strlen($request["picture_color"]) > 0 ){

            $comparisonPicture = new ComparisonPicture();

            //Color compare
            $comparisonPicture->setColorSearchValues();
            $comparisonPicture->threshold = 60;

            $image = imagecreatetruecolor(200, 200);
            // sets background to color

            $rgb = $this->hex2rgb($request["picture_color"]);
            $color = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);

            imagefill($image, 0, 0, $color);

            $name = "color_pic.png";
            $path = sys_get_temp_dir();

            imagepng($image, $path . "/" . $name);

            $comparisonPicture->image_path = $path . $name;
            $comparisonPicture->image_name = $name;

            $result = DbManager::Instance()->compare($comparisonPicture);
            $_SESSION['RESULTS'] = $result;

            $this->reroute('index.php?view=results');
        }
    }

    public function search($request) {

        $searchData = new SearchData();

        //Type: DisplayPicture
        $result;

        if (isset($request["search_all"]) && strlen($this->prepareInput($request["search_all"])) > 0) {

            $searchAll = $this->prepareInput($request["search_all"]);
            $searchData->txtDefault = $searchAll;

            $result = DbManager::Instance()->searchAll($searchData);
            $_SESSION['RESULTS'] = $result;

            $this->reroute('index.php?view=results');
        } else {

            $pictureName = $this->prepareInput($request["search_picture_name"]);
            $artist = $this->prepareInput($request["search_picture_artist"]);
            $museum = $this->prepareInput($request["search_picture_museum"]);
            $owner = $this->prepareInput($request["search_picture_owner"]);
            $keywords = $this->prepareKeywords($request["search_picture_keywords"]);
            $description = $this->prepareInput($request["search_picture_decription"]);
            $categories = NULL;
            if (isset($request["search_category"]))
              $categories = $this->mapCategories($request["search_category"]);

            $searchData->txtPictureName = $pictureName;
            $searchData->txtArtist = $artist;
            $searchData->txtOwner = $owner;
            $searchData->txtDescription = $description;
            $searchData->keywords = $keywords;
            $searchData->categories = $categories;

            $result = DbManager::Instance()->searchDetails($searchData);
            $_SESSION['RESULTS'] = $result;

            $this->reroute('index.php?view=results');
        }
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
      // Check is simplified
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
          return;
	    	}

        if (!$isMuseumOwner && !$isMuseumExhibitor) {

          echo "Museum is neither Owner nor Exhibitor. Needs to be at least one of the two.";
          return;
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

    private function reroute($path) {

      $host  = $_SERVER['HTTP_HOST'];
      $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
      $extra = $path;

      if (headers_sent()) {
        die("<br/><br/><b>Redirect failed. Please click on this link:</b> <a href=\"http://$host$uri/$extra\"> Redirect</a>");
      }
      else{
        exit(header("Location: http://$host$uri/$extra"));
      }
    }

    private function mapCategories($categories) {

        if (count($categories) == 0)
        return NULL;

        array_walk($categories, function(&$key, $value) {

            $category = new Category();
            $category->id = $key;
            $category->title = "TODO: Mapping";

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

    function hex2rgb($hex) {
       $hex = str_replace("#", "", $hex);

       if(strlen($hex) == 3) {
          $r = hexdec(substr($hex,0,1).substr($hex,0,1));
          $g = hexdec(substr($hex,1,1).substr($hex,1,1));
          $b = hexdec(substr($hex,2,1).substr($hex,2,1));
       } else {
          $r = hexdec(substr($hex,0,2));
          $g = hexdec(substr($hex,2,2));
          $b = hexdec(substr($hex,4,2));
       }
       $rgb = array($r, $g, $b);
       //return implode(",", $rgb); // returns the rgb values separated by commas
       return $rgb; // returns an array with the rgb values
    }

}

?>
