<?php
class NotFoundController extends Controller {

    public function __construct() {
        parent::__construct();
            $login = new Login();
        
        if(!$login->islogin()){
            header('Location: '.URL.'/Login');
        }
    }

    public function index() {
        $data = array();
        
        $this->loadView('404', $data);
    }

}