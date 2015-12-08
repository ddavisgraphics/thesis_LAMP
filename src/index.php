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

    templates::display('header');
?>

<section>
    <header>
        <h1>{local var="pageHeader"}</h1>
    </header>

    {local var="content"}
</section>


<?php
    templates::display('footer');
?>