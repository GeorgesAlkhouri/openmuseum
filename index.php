<?php

    include("classes/controller/IController.php");
    include("classes/controller/NavigationController.php");
    include("classes/controller/HeaderController.php");
    include("classes/controller/BodyController.php");
    include("classes/view/View.php");
    include("classes/model/Artist.php");
    include("classes/model/Category.php");
    include("classes/model/Museum.php");
    include("classes/model/Owner.php");
    include("classes/model/Picture.php");
    include("classes/model/SearchData.php");
    include("includes/DbManager.php");

    $request = array_merge($_GET, $_POST, $_FILES);
    
    $headerController = new HeaderController($request);
    $navigationController = new NavigationController($request);
    $bodyController = new BodyController($request);

    echo $headerController->display();
    echo $navigationController->display();
    echo $bodyController->display();
?>
