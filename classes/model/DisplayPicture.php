<?php

class DisplayPicture{

	public $id;

	public $name;
    public $description;
    public $creation_date;
    public $upload_date;
    public $artist; // Artist object
    public $artist_safety_level;
    public $museum_owns; // Museum object
    public $museum_exhibits; // Museum object
    public $museum_exhibits_startdate;
    public $museum_exhibits_enddate;
    public $owner; // Owner object

    public $image_data; // blob data
}

?>