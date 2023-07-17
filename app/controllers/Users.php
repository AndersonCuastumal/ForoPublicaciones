<?php

class Users extends Controller {

    public function __construct(){
            $this->userModel=$this->model('User');
    }

    public function register(){
        // Check for post
        if($_SERVER['REQUEST_METHOD']=='POST'){

            //
            // Process form
            // Sanitize POST data
            $_POST=filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);
            //Init data
            $data=[
                'name'=>trim($_POST['name']),
                'email'=>trim($_POST['email']),
                'password'=>trim($_POST['password']),
                'confirm_password'=>trim($_POST['confirm_password']),
                'name_err'=>'',
                'email_err'=>'',
                'password_err'=>'',
                'confirm_password_err'=>''
            ];

            //validate Email
            if(empty($data['email'])){
                $data['email_err']='Please enter email';
            }else{
                // cheked email
                if($this->userModel->findUserByEmail($data['email'])){
                    $data['email_err']='Email is already taken';
                    
                }
            }
            //validate Name
            if(empty($data['name'])){
                $data['name_err']='Please enter name';
            }

            //validate password
            if(empty($data['password'])){
                $data['password_err']='Please enter password';
            }elseif(strlen($data['password'])<6){
                $data['password_err']='Password must be at least 6 characters';
            }
            //validate confirm_password
            if(empty($data['confirm_password'])){
                $data['confirm_password_err']='Please enter confirm password';
            }else{
                if($data['password']!=$data['confirm_password']){
                    $data['confirm_password_err']='Passwords do not match';
                }
            }

            // Make sure errors are empty
            if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
                // validated

                // encode password or hash password
                $data['password']=password_hash($data['password'],PASSWORD_DEFAULT);

                // Register user
                if($this -> userModel->register($data)){
                    flash('register_success','You are now registered and can log in');
                    redirect('users/login');
                }else{
                    die('Something went wrong');
                }
            }else{
                // load view with errors
                $this->view('users/register',$data);
            }

        }else{
        
            //Load form
            $data=[
                'name'=>'',
                'email'=>'',
                'password'=>'',
                'confirm_password'=>'',
                'name_err'=>'',
                'email_err'=>'',
                'password_err'=>'',
                'confirm_password_err'=>''
            ];
            $this ->view('users/register',$data);
        }
    }


    public function login(){
        // Check for post
        if($_SERVER['REQUEST_METHOD']=='POST'){
            // Process form

            $_POST=filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);
            //Init data
            $data=[

                'email'=>trim($_POST['email']),
                'password'=>trim($_POST['password']),


                'email_err'=>'',
                'password_err'=>''

            ];

            //validate Email
            if(empty($data['email'])){
                $data['email_err']='Please enter email';
            }


            //validate password
            if(empty($data['password'])){
                $data['password_err']='Please enter password';
            }elseif(strlen($data['password'])<6){
                $data['password_err']='Password must be at least 6 characters';
            }


            // check for user/email

            if($this->userModel->findUserByEmail($data['email'])){
                //user found

            }else{
                // user not found
                $data['email_err']='No user found';
            }
            // Make sure errors are empty
            if(empty($data['email_err']) && empty($data['password_err']) ){
                // Validated

                //Check and set logged in user
                $loggedInUser=$this->userModel->login($data['email'],$data['password']);
                if($loggedInUser){
                    //Create session
                    $this ->createUserSession($loggedInUser);
                }else{
                    $data['password_err']='Password incorrect';
                    $this->view('users/login',$data);
                }

            }else{
                // load view with errors
                $this->view('users/login',$data);
            }


        }else{
        
            //Load form
            $data=[
  
                'email'=>'',
                'password'=>'',
                'email_err'=>'',
                'password_err'=>'',

            ];
            $this ->view('users/login',$data);
        }
    }

    // start session
    public function createUserSession($user){
        $_SESSION['user_id']=$user->id;
        $_SESSION['user_email']=$user->email;
        $_SESSION['user_name']=$user->name;
        redirect('posts');
    }

    // closed session
    public function logout(){
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_email']);
        session_destroy();
        redirect('pages/login');
    }


}