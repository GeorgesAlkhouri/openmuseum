<?php

include_once 'DbManager.php';

//uploadData();

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

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <h1>Hello, world!</h1>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>