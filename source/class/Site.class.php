<?php

class Site extends SiteObject{
	
	public $links;
	public $modules;
	public $request;
	
	public function __construct($_CONFIG) {
		$this->request = new Request($_CONFIG);
		$this->config = $this->request->config;
	}
	
	public function validateAPI() {
		if($this->config->enableAPI AND isset($this->request->apikey) AND $this->request->apikey===$this->config->apikey) {
			$_SESSION['username']='API';
			return $this->request->apikey===$this->config->apikey;
		}else{
			return false;
		}
	}
}