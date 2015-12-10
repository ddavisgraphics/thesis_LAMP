<?php
    require_once "includes/engine.php";
    require_once "includes/models/index.php";
    require_once "includes/controller/index.php";

    // Stuff to Render
    $today = date('d-m-y');
    $localvars  = localvars::getInstance();
    $localvars->set('date', $today);
    $localvars->set('pageName', ucfirst($this->data['model']));
?>
<h2>404 Error</h2>
<p> <strong>{local var="pageName"}</strong>  - {local var="date"}</p>
<p> The Page you requested failed.  Maybe the wrong name, or maybe the page doesn't exsist. </p>