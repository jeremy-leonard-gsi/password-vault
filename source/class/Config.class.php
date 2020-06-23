<?php

class Config extends SiteObject{

	public $db;

	public function __construct($_CONFIG) {
		$this->write($_CONFIG);
		$this->db = new PDO($this->configDSN,$this->configUsername, $this->configPassword);
		$this->readConfig();
	}
	
	private function readConfig(){
		$query = "SELECT * FROM config";
		$stmt = $this->db->query($query);
		$_CONFIG = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach($_CONFIG as $config){
			$this->write([$config['key']=>$config['value']]);		
		}
	}
}