<?php

class Artist{

	public $id;
	public $firstname;
    public $lastname;
    public $birth_date;
    public $death_date;

    public function __toString() {

    	return $this->firstname . " " . $this->lastname . " " . $this->birth_date . " " . $this->death_date;
    }
}

?>