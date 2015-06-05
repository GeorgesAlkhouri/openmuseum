<?php

class Museum{

	public $id;

	public $name;
    public $adress;
    public $website;

    public $isExhibitor;
    public $isOwner;

    public function __toString() {

		return 	$this->name . " " . 
				$this->adress . " " . 
				$this->website . " isExhibitor: " . 
				var_export($this->isExhibitor, true) . " isOwner: " . 
				var_export($this->isOwner, true);
	}
}
?>