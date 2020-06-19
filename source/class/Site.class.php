<?php

class Site extends SiteObject{
	
	public $config;
	public $links;
	public $modules;
	public $request;
	
	public function __construct($_CONFIG) {
		$this->request = new Request();
		$this->config = new Config($_CONFIG);
	}
}