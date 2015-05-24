<?php

    include("classes/controller/IController.php");
    include("classes/controller/NavigationController.php");
    include("classes/controller/HeaderController.php");
    include("classes/controller/BodyController.php");
    include("classes/view/View.php");
    include("classes/model/Artist.php");

    $request = array_merge($_GET, $_POST, $_FILES);

    $headerController = new HeaderController($request);
    $navigationController = new NavigationController($request);
    $bodyController = new BodyController($request);

    echo $headerController->display();
    echo $navigationController->display();
    echo $bodyController->display();
?>
