<?php
    require_once "includes/engine.php";
    require_once "includes/models/index.php";
    require_once "includes/controller/index.php";

    $router   = router::getInstance();
    $router->defineRoute("/", 'displayHome');
    $router->defineRoute("/{model}", 'displayRoute');
    $router->defineRoute("/{model}/{action}", 'displayRoute');
    $router->defineRoute("/{model}/{action}/{item}", 'displayRoute');
    $router->route();

    $customers = new Customers;
    // $customers->getRecords();  // read
    // $customers->setupForm();   // create / update

    templates::display('header');
?>

{local var="content"}

<?php
    templates::display('footer');
?>