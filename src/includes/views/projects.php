<?php
    require_once "includes/engine.php";
    require_once "includes/models/index.php";
    require_once "includes/controller/index.php";
    require_once "includes/functions/index.php";

    // instantiate classes
    $localvars = localvars::getInstance();
    $validate  = new validate;

    // set template vars
    $localvars->set('pageName', ucfirst($this->data['model']));

    // see what we are trying to view
    $model  = $this->data['model'];
    $action = $this->data['action'];
    $item   = $this->data['item'];

    // set output and set local variable for html display
    $output = determineAction($model, $action, $item);
    $localvars->set('pageContent', $output);
?>

<div class="wrapper">
    <div class="container">
        <h2> {local var="pageName"} </h2>
        {local var="pageContent"}
    </div>
</div>


