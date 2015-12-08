<?php

  function displayRoute($url, $vars){
    $model  = $vars['model'];
    $action = $vars['action'];
    $item   = $vars['item'];

    $expectedModels = array(
      'customers', 'projects', 'timeTracker'
    );

    if(in_array($model, $expectedModels)){
      print "Yay lets do something";
    } else {
      print "404";
    }

    $pageHeader =  $model;
  }

  function displayHome($url, $vars){
    $localvars  = localvars::getInstance();

    $view = new View('Home', array());
    $html = $view->render();
    $localvars->set('content', $html);
  }

?>