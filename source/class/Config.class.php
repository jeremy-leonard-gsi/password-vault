<?php

class Config extends SiteObject{

	private $db;        
        public $hiddenFields;

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
            if($this->debug){
                error_log("Reading config from database");
            }
            $query = "SELECT * FROM config";
            $stmt = $this->db->query($query);
            $_CONFIG = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($_CONFIG as $config){
                    $this->write([$config['key']=>$config['value']]);		
            }
	}
        public function __get($name) {
            error_log("Property: $name");
            $encodedValues = [
                'authLDAPSecret',
                'pwvPassword'
            ];
            if(in_array($name, $encodedValues)){
                return base64_decode($this->properties[$name]) ?? false;
            }else{
                return $this->properties[$name] ?? false;
            }
        }
}