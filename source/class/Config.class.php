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
            if($this->properties['debug']){
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
                error_log($this->properties[$name]);
                return base64_decode($this->properties[$name]) ?? false;
            }else{
                error_log($this->properties[$name]);
                return $this->properties[$name] ?? false;
            }
        }
}