<?php
    require_once "includes/engine.php";
    require_once "includes/models/index.php";
    require_once "includes/controller/index.php";
    require_once "includes/functions/index.php";

    // instantiate classes
    $localvars = localvars::getInstance();
    $validate  = new validate;
    $customers = new Customers;

    // set template vars
    $localvars->set('pageName', ucfirst($this->data['model']));

    // see what we are trying to view
    $model  = $this->data['model'];
    $action = $this->data['action'];
    $item   = $this->data['item'];

    $output = determineAction($action, $item);


    print "<pre>";
    var_dump($output);
    print "</pre>";
?>

<h2> Manage {local var="pageName"} </h2>

{local var="pageContent"}


