<?php
    $root = $_SERVER['DOCUMENT_ROOT'];

    require_once $root."/includes/engine.php";
    require_once $root."/includes/models/index.php";
    require_once $root."/includes/controller/index.php";
    require_once $root."/includes/functions/index.php";

    $customer = new Customers;
    $validate = new validate;

    if(isset($_GET['mysql']['id']) && $validate->integer($_GET['mysql']['id'])){
        $data = $customer->getJSON($_GET['mysql']['id']);
    } else {
        $data = $customer->getJSON();
    }

    header('Content-Type: application/json');
    print $data;
?>