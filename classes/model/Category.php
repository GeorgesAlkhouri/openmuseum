<?php

class Category{

	public $id;

	public $title;

	public function __toString() {

		return 	"ID: " . $this->id . "<br />" .
				"Title: ". $this->title;
	}
}

?>