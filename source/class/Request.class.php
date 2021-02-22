<?php

class Request extends SiteObject{

	public function __construct() {
		$this->write(array('get'=>$_GET));
		$this->write(array('post'=>$_POST));
		if(isset($this->config->base)){
		    error_log($this->config->base);
    		$this->module = trim(parse_url($_SERVER["REQUEST_URI"],PHP_URL_PATH),'/');
		}else{
    		$this->module = trim(parse_url($_SERVER["REQUEST_URI"],PHP_URL_PATH),'/');
		}
		$this->action = isset($this->post->action)?$this->post->action:null;
		$this->method = $_SERVER["REQUEST_METHOD"];							
	}
}