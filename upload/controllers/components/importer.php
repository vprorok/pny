<?php
    App::import('Vendor', 'importer'.DS.'importer');
    App::import('Vendor', 'importer'.DS.'aol');
    App::import('Vendor', 'importer'.DS.'gmail');
    App::import('Vendor', 'importer'.DS.'hotmail');
    App::import('Vendor', 'importer'.DS.'msn_mail');
    App::import('Vendor', 'importer'.DS.'yahoo');

    global $_CONFIG;
    $_CONFIG['COOKIE_DIR'] = TMP.'cache'.DS.'importer';

    class ImporterComponent extends Object{
        function aol($data){
            if(!empty($data['login']) && !empty($data['password'])){
                $service = new aol();
                $result = $service->run($data['login'], $data['password']);
                if(!empty($result)){
                    return $result;
                }else{
                    return false;
                }
            }
        }

        function gmail($data){
            if(!empty($data['login']) && !empty($data['password'])){
                $service = new gmail();
                $result = $service->run($data['login'], $data['password']);
                if(!empty($result)){
                    return $result;
                }else{
                    return false;
                }
            }
        }

        function hotmail($data){
            if(!empty($data['login']) && !empty($data['password'])){
                $service = new hotmail();
                $result = $service->run($data['login'], $data['password']);
                if(!empty($result)){
                    return $result;
                }else{
                    return false;
                }
            }
        }

        function msn_mail($data){
            if(!empty($data['login']) && !empty($data['password'])){
                $service = new msn_mail();
                $result = $service->run($data['login'], $data['password']);
                if(!empty($result)){
                    return $result;
                }else{
                    return false;
                }
            }
        }

        function yahoo($data){
            if(!empty($data['login']) && !empty($data['password'])){
                $service = new yahoo();
                $result = $service->run($data['login'], $data['password']);
                if(!empty($result)){
                    return $result;
                }else{
                    return false;
                }
            }
        }
    }
?>
