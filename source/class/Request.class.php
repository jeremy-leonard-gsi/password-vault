<?php

class Request extends SiteObject{

	public function __construct(Config $config) {
            $this->write(array('get'=>$_GET));
            $this->write(array('post'=>$_POST));
            foreach($_GET as $key => $value){
                    $this->{$key}=filter_input(INPUT_GET,$key);
            }
            foreach($_POST as $key => $value){
                    $this->{$key}=filter_input(INPUT_POST,$key);
            }
            if(isset($config->base)){
                    $base = $config->base;
            $this->module = trim(str_replace($base,'',parse_url($_SERVER["REQUEST_URI"],PHP_URL_PATH)),'/');
            }else{
                    $config->base='';
            $this->module = trim(parse_url($_SERVER["REQUEST_URI"],PHP_URL_PATH),'/');
            }
            $this->action = isset($this->post->action)?$this->post->action:null;
            $this->method = $_SERVER["REQUEST_METHOD"];							
	}
}