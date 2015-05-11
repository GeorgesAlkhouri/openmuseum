<?php

include_once 'DbManager.php';

uploadData();

function uploadData() {
    
    // TODO: replace data with real user data after validation
    $artist = new Artist();
    $artist->firstname = 'Leonardo';
    $artist->lastname = 'da Vinci';
    $artist->birth_date = '1452/04/15';
    $artist->death_date = '1519/05/02';
    
    $owner = new Owner();
    $owner->firstname = 'Bill';
    $owner->lastname = 'Gates';
    
    $museum = new Museum();
    $museum->name = 'Louvre';
    $museum->adress = 'Paris';
    $museum->website = 'http://www.louvre.fr/en';
    $museum->isExhibitor = true;
    $museum->isOwner = true;
    
    $keywords = array("Ölgemälde", "Frau", "Balkon");
    
    $category_ids = array(1, 2, 5);
    
    $picture = new Picture();
    $picture->name = "Mona Lisa";
    $picture->description = "Mona Lisa ist ein weltberühmtes Ölgemälde von Leonardo da Vinci.";
    $picture->creation_date = '1503/01/01';
    $picture->upload_date = '2015/05/10';
    $picture->artist_safety_level = 100;
    $picture->museum_exhibits_startdate = '1887/01/01';
    $picture->museum_exhibits_enddate = '2015/05/15';

    $dbManager = DbManager::Instance();
    $dbManager->insertUserDataInDb($picture, $artist, $museum, $owner, $keywords, $category_ids);
}

function __autoload($class_name) {
    include_once 'model/'.$class_name . '.php';
}

?>