<?php

class Category{

	public $id;

	public $title;

	public function __toString() {

		return $this->title;
	}
}

?>