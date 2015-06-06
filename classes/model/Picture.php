<?php

class Picture{

	public $id;

	public $name;
    public $description;
    public $creation_date;
    public $upload_date;
    public $artist_fk;
    public $artist_safety_level;
    public $museum_owns_fk;
    public $museum_exhibits_fk;
    public $museum_exhibits_startdate;
    public $museum_exhibits_enddate;
    public $owner_fk;

    public $image_path;
    public $image_name;

    public function __toString() {

        return $this->name . " " . $this->description . " " . $this->creation_date . " " . $this->upload_date;
    }

    public function debug() {

        return "artist_fk: " . $this->artist_fk . "<br />" .
                "museum_owns_fk: " . $this->museum_owns_fk . "<br />" .
                "museum_exhibits_fk: " . $this->museum_exhibits_fk . "<br />" .
                "museum_exhibits_startdate: " . $this->museum_exhibits_startdate . "<br />" .
                "museum_exhibits_enddate: " . $this->museum_exhibits_enddate . "<br />" .
                "owner_fk: " . $this->owner_fk;
    }
}

?>