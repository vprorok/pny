<?php
    /**
     * List controller an actionse component
     */
    class ControllerListComponent extends Object{
        function get(){
            return $this->_getControllers();
        }

        function _getControllers(){
            $controllerList = Configure::listObjects('controller');
            $controllers= array();

            foreach($controllerList as $controller){
                $file = APP."controllers".DS.Inflector::underscore($controller)."_controller.php";

                if(file_exists($file)){
                    $controllers[$controller] = $this->_getControllerMethods($controller);
                }
            }

            return $controllers;
        }

        function _getControllerMethods($controllerName){
            $classMethodsCleaned = array();
            $method_exception = array('login', 'admin_login');

            $file = APP."controllers".DS.Inflector::underscore($controllerName)."_controller.php";

            require_once($file);

            $parentClassMethods = get_class_methods('Controller');
            $subClassMethods = get_class_methods(Inflector::camelize($controllerName).'Controller');
            $classMethods = array_diff($subClassMethods, $parentClassMethods);

            foreach($classMethods as $method){
                if($method{0} <> "_" && !in_array($method, $method_exception)){
                    $classMethodsCleaned[] = $method;
                }
            }
            return $classMethodsCleaned;
        }
    }

?>
