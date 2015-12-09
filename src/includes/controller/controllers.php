<?php
    function displayRoute($url, $vars){
        // new instance of local variables
        $localvars  = localvars::getInstance();

        // declar url vars
        $model  = $vars['model'];
        $action = isset($vars['action']) ? $vars['action'] : null;
        $item   = isset($vars['item'])   ? $vars['item']   : null;

        // expected pages
        $expectedModels = array(
            'customers', 'projects', 'timeTracker'
        );

        if(in_array($model, $expectedModels)){
             $pageVariables = array(
                'model'  => ucfirst($model),
                'action' => $action,
                'item'   => $item
            );

            $view = new View($model, $pageVariables);
            $html = $view->render();
            $localvars->set('content', $html);
        }
        else {
            $pageVariables = array(
                'model' => ucfirst($model)
            );

            $view = new View('Error', $pageVariables);
            $html = $view->render();
            $localvars->set('content', $html);
        }
    }

  function displayHome($url, $vars){
    $localvars  = localvars::getInstance();

    $view = new View('Home', array());
    $html = $view->render();
    $localvars->set('content', $html);
  }

?>