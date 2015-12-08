<?php
    class Customers {
        public function getCustomer($id = null){
            try {
                // call engine
                $engine    = EngineAPI::singleton();
                $localvars = localvars::getInstance();
                $db        = db::get($localvars->get('dbConnectionName'));
                $sql       = "SELECT * FROM `customers`";

                // test to see if Id is present and valid
                if(!isnull($id) && validate::integer($id)){
                    $sql .= sprintf('WHERE id = %s LIMIT 1', $id);
                }

                // if no valid id throw an exception
                if(!validate::integer($id)){
                    throw new Exception(__METHOD__.'() - Not a valid integer, please check the integer and try again.');
                }

                // get the results of the query
                $sqlResult = $db->query($sql);

                // if no results throw an exception
                // else return the data
                if ($sqlResult->rowCount() < 1) {
                   throw new Exception(__METHOD__.'() -There are no employees in the database');
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

        public function setupCustomerForm($id = null){
             try {
                // call engine
                $engine    = EngineAPI::singleton();
                $localvars = localvars::getInstance();
                $db        = db::get($localvars->get('dbConnectionName'));
                $sql       = "SELECT * FROM `customers`";

                // create customer form
                $form = formBuilder::createForm('Customers');
                $form->linkToDatabase( array(
                    'table' => 'customers'
                ));

                // form titles
                $form->insertTitle = "Add Customer";
                $form->editTitle   = "Edit Customer";
                $form->updateTitle = "Edit Customer";

                // if no valid id throw an exception
                if(!validate::integer($id)){
                    throw new Exception(__METHOD__.'() - Not a valid integer, please check the integer and try again.');
                }

                // form information
                 $form->addField(array(
                    'name'    => 'ID',
                    'type'    => 'hidden',
                    'value'   => $id,
                    'primary' => TRUE,
                    'fieldClass' => 'id',
                    'showIn'     => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE),
                ));

                $form->addField(array(
                    'name'     => 'firstName',
                    'label'    => 'First Name:',
                    'required' => TRUE
                ));

                $form->addField(array(
                    'name'     => 'lastName',
                    'label'    => 'Last Name:',
                    'required' => TRUE
                ));

                $form->addField(array(
                    'name'     => 'companyName',
                    'label'    => 'Company Name:',
                    'required' => TRUE
                ));

                $form->addField(array(
                    'name'     => 'email',
                    'label'    => 'Customer Email:',
                    'required' => TRUE
                ));

                $form->addField(array(
                    'name'     => 'phone',
                    'label'    => 'Customer Phone Number:',
                    'required' => TRUE
                ));

                $form->addField(array(
                    'name'            => "website",
                    'label'           => "Customers Website:",
                    'type'            => 'URL',
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
                    'fieldClass' => 'delete',
                    'value'      => 'Delete'
                ));

                $form->addField(array(
                    'showIn'     => array(formBuilder::TYPE_INSERT),
                    'name'       => 'insert',
                    'type'       => 'submit',
                    'fieldClass' => 'submit',
                    'value'      => 'Submit'
                ));


            } catch (Exception $e) {
                errorHandle::errorMsg($e->getMessage());
            }
        }
    }
?>