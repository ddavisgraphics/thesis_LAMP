<?php
    function determineAction($class, $action, $item){
        $localvars = localvars::getInstance();
        $validate  = new validate;
        $myClass   = new $class;
        $pageData  = "";

        // record Id Set to null
        $id = null;

        // create an array of valid actions
        $validActions = array('create', 'add', 'read', 'view', 'update', 'edit', 'delete');

        // this is an $id only
        // not null and not an empty string
        if(!isnull($item) && !is_empty($item) && $validate->integer($item)){
            $id = $item;
        }

        // get a specific record or determine what to do
        if(!isnull($action) || in_array($action, $validActions)){
            if($validate->integer($action)){
                $pageData = $myClass->getRecords($action);
            }
            else {
                switch ($action) {
                    case 'create':
                    case 'add':
                    case 'update':
                    case 'edit':
                        if(isnull($id)){
                            $pageData = $myClass->setupForm();
                        }
                        else{
                            $pageData = $myClass->setupForm($id);
                        }
                    break;

                    case 'delete':
                        if(!isnull($id)){
                            $pageData = $myClass->deleteRecords($id);
                        } else {
                            $pageData = $myClass->deleteRecords();
                        }
                    break;

                    default:
                    case 'read':
                    case 'view':
                        // if isnull $id get all records
                        if(isnull($id)){
                            $pageData = $myClass->getRecords();
                        }
                        else{
                            $pageData = $myClass->getRecords($id);
                        }
                    break;
                }
            }
        } else {
             $pageData = $myClass->getRecords();
        }

        return $pageData;
    }
?>