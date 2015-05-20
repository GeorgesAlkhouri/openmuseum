<?php

class SearchData{

	public $txtPictureName;
	public $txtArtist;
	public $txtMuseum;
	public $txtOwner;
	public $txtDescription;

	public $keywords; // Array of strings
	public $categories; //Array of ids

	public $sdImageComparison; // Class SDImageComparison
	public $sdColor; // Class SDColor. Example: $sdColor = SDColor::BlackWhite;
	public $sdForm; // Class SDForm. Example: $sdForm = SDForm::Circle;

}

?>