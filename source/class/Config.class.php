<?php

class Config extends SiteObject{

	private $db;
        protected $authLDAPSecret, $pwvPassword;
        public $hiddenFields, $encodedFields;

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
        $this->encodedFields = [
            'authLDAPSecret',
            'pwvPassword'
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
            if(in_array($name, $this->encodedFields)){
                error_log($this->$name);
                return base64_decode($this->$name) ?? false;
            }else{
                error_log($this->$name);
                return $this->$name ?? false;
            }
        }
}