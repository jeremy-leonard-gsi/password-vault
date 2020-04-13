<?php

class Site extends SiteObject{
	
	public $config;
	public $links;
	public $modules;
	
	public function __construct($_CONFIG) {
		$this->request = new Request();
		$this->config = new Config($_CONFIG);
		$this->loadLinks();
		$this->loadModules();
	}

	private function loadLinks() {
		$query = "SELECT * FROM `links` WHERE `enabled`=true ORDER BY `order`,`label`;";
		$stmt = $this->config->db->query($query);
		$this->write(array('links' => $stmt->fetchAll(PDO::FETCH_ASSOC)));
	}

	private function loadModules() {
		$query = "SELECT * FROM `modules` WHERE `enabled`=true ORDER BY `order`;";
		$stmt = $this->config->db->query($query);
		$this->write(array('modules' => $stmt->fetchAll(PDO::FETCH_ASSOC)));
	}
}