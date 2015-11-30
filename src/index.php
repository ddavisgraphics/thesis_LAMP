<?php
    require_once "includes/engine.php";
    require_once "includes/models/models.php";

    $router   = router::getInstance();
    $router->defineRoute("/", 'displayHome');
    $router->defineRoute("/{model}", 'displayRoute');
    $router->defineRoute("/{model}/{item}", 'displayRoute');
    $router->defineRoute("/{model}/{item}/{test}", 'displayRoute');
    $router->route();

    print "Testing";

?>
