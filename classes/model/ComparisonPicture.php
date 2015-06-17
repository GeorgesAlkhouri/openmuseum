<?php

class ComparisonPicture{

	public $path;
	public $name;
	public $weightColor; // 0.0 - 1.0, the higher, the more weight
	public $weightTexture; // 0.0 - 1.0, the higher, the more weight
	public $weightShape; // 0.0 - 1.0, the higher, the more weight
	public $weightLocation; // 0.0 - 1.0, the higher, the more weight
	public $threshold; // 0 - 100, the higher, the more results

	fuction setColorSearchValues(){
		$this->weightColor = 1.0;
		$this->weightTexture = 0.0;
		$this->weightShape = 0.0;
		$this->weightLocation = 0.0;
	}

	fuction setShapeSearchValues(){
		$this->weightColor = 0.0;
		$this->weightTexture = 0.0;
		$this->weightShape = 1.0;
		$this->weightLocation = 0.0;
	}

	fuction setTextureSearchValues(){
		$this->weightColor = 0.0;
		$this->weightTexture = 1.0;
		$this->weightShape = 0.0;
		$this->weightLocation = 0.0;
	}
}

?>