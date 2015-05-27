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
}

?>