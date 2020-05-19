<?php 
    define('VERSION_PATH',  $settings['version_path']);
    
    spl_autoload_register(function($className){
        $path = VERSION_PATH . '/' . strtolower($className) . '.php';

        if(file_exists($path)) {
            require_once($path);
        } else {
            echo 'File $path is not found.';
        }
    })
?>
 