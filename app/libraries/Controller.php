<?php

/*
*Base controller
* locads the models and views
*/

class Controller{
    //locad model
    public function model($model){
        // require model file
        require_once '../app/models/'.$model.'.php';
        //instatiate model
        return new $model();
    }

    //Load view
    public function view($view,$data=[]){
        // check for view file
        if(file_exists('../app/views/'.$view.'.php')){
            require_once '../app/views/'.$view.'.php';
        }else{
            die('View does not exist');
        }
    }
}