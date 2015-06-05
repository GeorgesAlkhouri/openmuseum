<?php

class Owner{

	public $id;

	public $firstname;
    public $lastname;

    public function __toString() {

    	return $this->firstname . " " . $this->lastname;
    }
}

?>