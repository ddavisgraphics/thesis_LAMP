<?php
    class View {
        public $templateExsist;
        public $path;
        public $variables;
        public $data;

        public function __construct($template, $variables = array()){
            try {
                $viewsPath = '/home/timeTracker/public_html/src/includes/views/';
                $file      = $viewsPath . strtolower($template) . '.php';

                $this->path      = $file;
                $this->variables = $variables;
                $this->extractVariables();

                if (file_exists($file)) {
                    $this->templateExsist = true;
                } else {
                    throw new Exception('Template ' . $template . ' not found!');
                }
            }
            catch(Exception $e) {
                errorHandle::errorMsg($e->getMessage());
                $this->templateExsist = false;
            }
        }

        public function render() {
            try {
                $file = $this->path;

                if(isnull($file)){
                    throw new Exception('Path is null.  We can not have null paths so something is crazy');
                }

                ob_start();
                include($file);
                $renderedView = ob_get_contents();
                ob_end_clean();

                return $renderedView;
            } catch(Exception $e) {
                errorHandle::errorMsg($e->getMessage());
            }
        }

        private function extractVariables(){
            $variables = $this->variables;
            foreach($variables as $varName => $varValue){
                $this->data[$varName] = $varValue;
            }
        }
    }
?>