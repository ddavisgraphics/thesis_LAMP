<?php
class Projects {
    public function getRecords($id = null){
        try {
            // call engine
            $engine    = EngineAPI::singleton();
            $localvars = localvars::getInstance();
            $db        = db::get($localvars->get('dbConnectionName'));
            $sql       = "SELECT * FROM `projects`";
            $validate  = new validate;

            // test to see if Id is present and valid
            if(!isnull($id) && $validate->integer($id)){
                $sql .= sprintf('WHERE projectID = %s LIMIT 1', $id);
            }

            // if no valid id throw an exception
            if(!$validate->integer($id) && !isnull($id)){
                throw new Exception("An invalid ID was given!");
            }

            // get the results of the query
            $sqlResult = $db->query($sql);

            // if return no results
            // else return the data
            if ($sqlResult->rowCount() < 1) {
               return "There are no projects in the database.";
            }
            else {
                $data = array();
                while($row = $sqlResult->fetch()){
                    $data[] = $row;
                }
                return $data;
            }
        } catch (Exception $e) {
            errorHandle::errorMsg($e->getMessage());
        }
    }

    public function setupForm($id = null){
         try {
            // call engine
            $engine    = EngineAPI::singleton();
            $localvars = localvars::getInstance();
            $validate  = new validate;

            // create customer form
            $form = formBuilder::createForm('Projects');
            $form->linkToDatabase( array(
                'table' => 'projects'
            ));

            if(!is_empty($_POST) || session::has('POST')) {
                $processor = formBuilder::createProcessor();
                $processor->processPost();
            }

            // form titles
            $form->insertTitle = "Add Project";
            $form->editTitle   = "Edit Project";
            $form->updateTitle = "Edit Project";

            // if no valid id throw an exception
            if(!$validate->integer($id) && !isnull($id)){
                throw new Exception(__METHOD__.'() - Not a valid integer, please check the integer and try again.');
            }

            // form information
            $form->addField(array(
                'name'       => 'projectID',
                'type'       => 'hidden',
                'value'      => $id,
                'primary'    => TRUE,
                'fieldClass' => 'id',
                'showIn'     => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE),
            ));

           $form->addField(array(
                'name'     => 'customerID',
                'label'    => 'What customer owns this project?',
                'type'     => 'select',
                'blankOption' => 'Select a Customer',
                'linkedTo' => array(
                    'foreignTable' => 'customers',
                    'foreignField' => 'id',
                    'foreignLabel' => 'companyName',
                ),
            ));

            $form->addField(array(
                'name'     => 'projectName',
                'label'    => 'Project Name:',
                'required' => TRUE
            ));

            $form->addField(array(
                'name'     => 'scope',
                'label'    => 'A simple statement of the scope of work being done:',
                'required' => TRUE
            ));

            $form->addField(array(
                'name'     => 'type',
                'label'    => 'Customer Email:',
                'required' => TRUE,
                'type'     => 'select',
                'options'  =>  array(
                    'design'      => 'Design',
                    'development' => 'Programming or Development',
                    'consult'     => 'Meeting or Consultation',
                    'other'       => 'other'
                ),
            ));

            $form->addField(array(
                'name'            => "completed",
                'label'           => "Has this project been completed?",
                'showInEditStrip' => TRUE,
                'type'            => 'boolean',
                'duplicates'      => TRUE,
                'options'         => array("YES","N0")
            ));

            $form->addField(array(
                'name'            => "description",
                'label'           => "Enter a description of the project:",
                'type'            => 'textarea',
            ));

            // buttons and submissions
            $form->addField(array(
                'showIn'     => array(formBuilder::TYPE_UPDATE),
                'name'       => 'update',
                'type'       => 'submit',
                'fieldClass' => 'submit',
                'value'      => 'Update'
            ));

            $form->addField(array(
                'showIn'     => array(formBuilder::TYPE_UPDATE),
                'name'       => 'delete',
                'type'       => 'delete',
                'fieldClass' => 'delete hidden',
                'value'      => 'Delete'
            ));

            $form->addField(array(
                'showIn'     => array(formBuilder::TYPE_INSERT),
                'name'       => 'insert',
                'type'       => 'submit',
                'fieldClass' => 'submit',
                'value'      => 'Submit'
            ));

            return '{form name="Projects" display="form"}';

        } catch (Exception $e) {
            errorHandle::errorMsg($e->getMessage());
        }
    }

    public function deleteRecord($id = null){
        try {
            // call engine
            $engine    = EngineAPI::singleton();
            $localvars = localvars::getInstance();
            $db        = db::get($localvars->get('dbConnectionName'));
            $validate  = new validate;

            // test to see if Id is present and valid
            if(isnull($id) || !$validate->integer($id)){
                throw new Exception(__METHOD__.'() -Delete failed, improper id or no id was sent');
            }

            // SQL Results
            $sql = sprintf("DELETE FROM `customers` WHERE id=%s LIMIT 1", $id);
            $sqlResult = $db->query($sql);

            if(!$sqlResult) {
                throw new Exception(__METHOD__.'Failed to delete Projects.');
            }
            else {
                return "Successfully deleted the message";
            }

        } catch (Exception $e) {
            errorHandle::errorMsg($e->getMessage());
            return $e->getMessage();
        }
    }


    public function renderDeleteData($id){
        try {
            $engine    = EngineAPI::singleton();
            $localvars = localvars::getInstance();
            $validate  = new validate;

            if(isnull($id) || !$validate->integer($id)){
                throw new Exception('Id is null or not an integer.  Please try again.');
            }
            else {
                $dataRecord = self::getRecords($id);
                $output = "";
                foreach($dataRecord as $data){
                     $output .= sprintf("<div class='customerRecord'>
                                            <h2 class='company'>%s</h2>
                                            <div class='name'>
                                                <strong>Customer Name:</strong>
                                                %s
                                            </div>
                                            <div class='contactInfo'>
                                                <div class='email'>%s</div>
                                                <div class='phone'>%s</div>
                                                <div class='website'><a href='%s'>%s</a></div>
                                            </div>
                                            <div class='actions'>
                                                <a href='/customers/delete/%s'> Delete </a>
                                                <a href='/customers'> Cancel </a>
                                            </div>
                                        </div>",
                            $data['companyName'],
                            $data['firstName']." ".$data['lastName'],
                            $data['email'],
                            $data['phone'],
                            $data['website'],
                            $data['website'],
                            $data['ID']
                    );
                }

                return $output;
            }

        } catch (Exception $e) {
            errorHandle::errorMsg($e->getMessage());
            return $e->getMessage();
        }
    }

    public function renderSingleRecord($id){
        try {
            $engine    = EngineAPI::singleton();
            $localvars = localvars::getInstance();
            $validate  = new validate;

            if(isnull($id) || !$validate->integer($id)){
                throw new Exception('Id is null or not an integer.  Please try again.');
            }
            else {
                $dataRecord = self::getRecords($id);

                if(!is_array($dataRecord)){
                    return $dataRecord;
                }

                $output = "";
                foreach($dataRecord as $data){
                    $output .= sprintf("<div class='customerRecord'>
                                            <h2 class='company'>%s</h2>
                                            <div class='name'>
                                                <strong>Customer Name:</strong>
                                                %s
                                            </div>
                                            <div class='contactInfo'>
                                                <div class='email'>%s</div>
                                                <div class='phone'>%s</div>
                                                <div class='website'><a href='%s'>%s</a></div>
                                            </div>
                                            <div class='actions'>
                                                <a href='/customers/edit/%s'> Edit Customer </a>
                                                <a href='/customers/delete/%s'> Delete Customer </a>
                                            </div>
                                        </div>",
                            $data['companyName'],
                            $data['firstName']." ".$data['lastName'],
                            $data['email'],
                            $data['phone'],
                            $data['website'],
                            $data['website'],
                            $data['ID'],
                            $data['ID']
                    );
                }

                return $output;
            }

        } catch (Exception $e) {
            errorHandle::errorMsg($e->getMessage());
            return $e->getMessage();
        }
    }

     public function renderDataTable(){
        try {
            $engine     = EngineAPI::singleton();
            $localvars  = localvars::getInstance();
            $validate   = new validate;
            $dataRecord = self::getRecords();

            $records    = "";

            foreach($dataRecord as $data){
                $records .= sprintf("<tr>
                                        <td>%s</td>
                                        <td>%s</td>
                                        <td>%s</td>
                                        <td>%s</td>
                                        <td>%s</td>
                                        <td>%s</td>
                                        <td><a href='customers/edit/%s'> Edit Customer </a></td>
                                        <td><a href='customers/confirmDelete/%s'> Delete Customer </a></td>
                                    </tr>",
                        $data['companyName'],
                        $data['firstName'],
                        $data['lastName'],
                        $data['email'],
                        $data['phone'],
                        $data['website'],
                        $data['ID'],
                        $data['ID']
                );
            }

            $output     = sprintf("<div class='dataTable'>
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th> Company Name </th>
                                                    <th> First name </th>
                                                    <th> Last Name </th>
                                                    <th> Email </th>
                                                    <th> Phone Number </th>
                                                    <th> Website </th>
                                                    <th> </th>
                                                    <th> </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                %s
                                            </tbody>
                                        </table>
                                    </div>",
                $records
            );

            return $output;

        } catch (Exception $e) {
            errorHandle::errorMsg($e->getMessage());
            return $e->getMessage();
        }
    }

    public function getJSON($id = null){
        if(!isnull($id) && $validate->integer($id)){
            $data = self::getRecords($id);
        } else {
            $data = self::getRecords();
        }
        return json_encode($data);
    }

}

?>