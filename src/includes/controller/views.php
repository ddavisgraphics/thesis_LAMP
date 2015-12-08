<?php
    class View {
        public $templateExsist;
        public $path;

        public function __construct($template, $variables = array()){
            try {
                $viewsPath = '/home/timeTracker/public_html/src/includes/views/';
                $file = $viewsPath . strtolower($template) . '.php';

                $this->path      = $file;
                $this->variables = $variables;

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

        public function render($file = null) {
            if(isnull($file)){
                $file = $this->path;
            }
            return file_get_contents($file);
        }

        // function render($template, $param){
        //    ob_start();
        //    include($template);//How to pass $param to it? It needs that $row to render blog entry!
        //    $ret = ob_get_contents();
        //    ob_end_clean();
        //    return $ret;
        // }
    }
?>