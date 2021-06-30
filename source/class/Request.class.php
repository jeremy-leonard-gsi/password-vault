<?php

class Request extends SiteObject{

	public $config;

	public function __construct($_CONFIG) {
		$this->config = new Config($_CONFIG);
		$this->write(array('get'=>$_GET));
		$this->write(array('post'=>$_POST));
		foreach($_GET as $key => $value){
			$this->{$key}=filter_input(INPUT_GET,$key);
		}
		foreach($_POST as $key => $value){
			$this->{$key}=filter_input(INPUT_POST,$key);
		}
		if(isset($this->config->base)){
		    	$base = $this->config->base;
            $this->module = trim(str_replace($base,'',parse_url($_SERVER["REQUEST_URI"],PHP_URL_PATH)),'/');
		}else{
			$this->config->base='';
    		$this->module = trim(parse_url($_SERVER["REQUEST_URI"],PHP_URL_PATH),'/');
		}
		$this->action = isset($this->post->action)?$this->post->action:null;
		$this->method = $_SERVER["REQUEST_METHOD"];							
	}
}