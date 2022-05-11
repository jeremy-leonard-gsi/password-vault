<?php

class Config extends SiteObject{

	private $db;
        private $pwvPassword;
        private $pwvSecret;
        private $authLDAPSecret;
        
        private $hiddenFields;

    public function __construct($_CONFIG) {
        $this->write($_CONFIG);
        $this->db = new PDO($this->configDSN,$this->configUsername, $this->configPassword);
        $this->readConfig();
        $this->hiddenFields = [
            'configDSN',
            'configUsername',
            'configPassword',
            'pwvSecret',
            'db'
        ];
        
    }
	
	private function readConfig(){
		$query = "SELECT * FROM config";
		$stmt = $this->db->query($query);
		$_CONFIG = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach($_CONFIG as $config){
			$this->write([$config['key']=>$config['value']]);		
		}
	}
        public function __set($name, $value) {
            $this->$name=$value;
        }
        
        public function __get($name) {
            error_log("Property: $name");
            $encodedValues = [
                'authLDAPSecret',
                'pwvPassword'
            ];
            if(in_array($name, $encodedValues)){
                return base64_decode($this->$name) ?? false;
            }else{
                return $this->$name ?? false;
            }
        }
}