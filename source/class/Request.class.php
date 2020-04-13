<?php

class Request extends SiteObject{

	public function __construct() {
		$this->write(array('get'=>$_GET));
		$this->write(array('post'=>$_POST));
		//$this->write(array('server'=>$_SERVER));
		$this->module = isset($this->get->module)?pathinfo($this->get->module,PATHINFO_FILENAME):null;
		$this->action = isset($this->post->action)?$this->post->action:null;
		$this->method = $_SERVER["REQUEST_METHOD"];							
	}
}