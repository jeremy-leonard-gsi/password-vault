<?php

class Site extends SiteObject{
	
	public $links;
	public $modules;
	public $request;
	
	public function __construct($_CONFIG) {
		$this->request = new Request($_CONFIG);
		$this->config = $this->request->config;
	}
}