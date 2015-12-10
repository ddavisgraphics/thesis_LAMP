<?php
    function determineAction($action, $item){
        $localvars = localvars::getInstance();
        $validate  = new validate;
        $customers = new Customers;

        // record Id Set to null
        $id = null;

        // create an array of valid actions
        $validActions = array('create', 'read', 'update', 'delete');

        // this is an $id only
        // not null and not an empty string
        if(!isnull($item) && !is_empty($item) && $validate->integer($item)){
            $id = $item;
        }

        // get a specific record or determine what to do
        if(!isnull($action) && !is_empty($action)){
            if($validate->integer($action)){
                $pageData = $customers->getRecords($action);
            }
            else {
                switch ($action) {
                    case 'create':
                    case 'update':
                        if(isnull($id)){
                            $pageData = $customers->setupForm();
                        }
                        else{
                            $pageData = $customers->setupForm($id);
                        }
                    break;

                    case 'delete':
                        if(!isnull($id)){
                            $pageData = $customers->deleteRecords($id);
                        } else {
                            $pageData = $customers->deleteRecords();
                        }
                    break;

                    default:
                    case 'read':
                        // if isnull $id get all records
                        if(isnull($id)){
                            $pageData = $customers->getRecords();
                        }
                        else{
                            $pageData = $customers->getRecords($id);
                        }
                    break;
                }
            }
        }

        return $pageData;
    }
?>