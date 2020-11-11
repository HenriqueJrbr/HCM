<?php
class Controller {

	protected $db;
	protected $helper;

	public function __construct() {                
		global $config;
		$this->db = new PDO("mysql:dbname=".$config['dbname'].";charset=utf8;host=".$config['host'], $config['dbuser'], $config['dbpass']);
		$this->helper = new Helper();                 
	}
	
	public function loadView($viewName, $viewData = array()) {
		extract($viewData);

		include 'views/'.$viewName.'.php';
	}

	public function loadTemplate($viewName, $viewData = array()) {	

		$sql = "SELECT * FROM z_sga_empresa";
      	$sql = $this->db->query($sql);

      	$array = array();
      	if($sql->rowCount()>0){
      		$array = $sql->fetchAll();
      	}

		include 'views/template.php';
	}

	public function loadViewInTemplate($viewName, $viewData) {
            setlocale(LC_TIME, 'pt_BR');
            date_default_timezone_set('America/Sao_Paulo');
            extract($viewData);

            include 'views/'.$viewName.'.php';
	}

}
