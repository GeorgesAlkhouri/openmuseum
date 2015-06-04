<?php

class SearchData{

	public $txtDefault;
	public $txtPictureName;
	public $txtArtist;
	public $txtMuseumOwnes;
	public $txtMuseumExhibits;
	public $txtOwner;
	public $txtDescription;

	public $keywords; // Array of strings
	public $categories; //Array of ids

	public $sdImageComparison; // Class SDImageComparison
	public $sdColor; // Class SDColor. Example: $sdColor = SDColor::BlackWhite;
	public $sdForm; // Class SDForm. Example: $sdForm = SDForm::Circle;

}

?>