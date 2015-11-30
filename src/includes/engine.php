<?php
    // path to my engineAPI install
    require_once '/home/timeTracker/phpincludes/engine/engineAPI/4.0/engine.php';
    $engine = EngineAPI::singleton();


    // Setup Error Rorting
    errorHandle::errorReporting(errorHandle::E_ALL);

    // These are specific to EngineAPI and pulling the appropriate files
    recurseInsert("includes/engine/vars.php","php");
    recurseInsert("headerIncludes.php","php");

    // Setup Database Information for Vagrant
    $databaseOptions = array(
        'username' => 'username',
        'password' => 'password',
        'dbName'   => 'timeTracker'
    );

    $db  = db::create('mysql', $databaseOptions, 'appDB');

    // Set localVars and engineVars variables
    $localvars  = localvars::getInstance();
    $enginevars = enginevars::getInstance();

    if (EngineAPI::VERSION >= "4.0") {
        $localvars  = localvars::getInstance();
        $localvarsFunction = array($localvars,'set');
    }
    else {
        $localvarsFunction = array("localvars","add");
    }

    // include base variables
    recurseInsert("includes/vars.php","php");
    recurseInsert("includes/controller/controllers.php", "php");

    // load a template to use
    templates::load('timeTemplate');
?>