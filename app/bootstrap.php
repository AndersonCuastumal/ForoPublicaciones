<?php

    //Load Config
    require_once 'config/config.php';
    // Load libraries
    /*require_once 'libraries/core.php';
    require_once 'libraries/controller.php';
    require_once 'libraries/database.php';
    */
    
    // Load helpers 
    require_once 'helpers/url_helper.php';
    require_once 'helpers/session_helper.php';

    //autoload core libraries

    spl_autoload_register(function($className){
        require_once 'libraries/'.$className.'.php';
    });
