<?php

class Config extends SiteObject{

	private $db;
        protected $authLDAPSecret, $pwvPassword;
        public $hiddenFields, $encodedFields;
        
        public $apikey;
        public $authLDAPBaseDN;
        public $authLDAPBindDN;
        public $authLDAPFilter;
        public $authLDAPFullnameAttribute;
        public $authLDAPURI;
        public $authLDAPUserAttribute;
        public $authType;
        public $base;
        public $debug;
        public $globalAdminGroupDN;
        public $groupDNs;
        public $logoURI;
        public $pwvDSN;
        public $pwvUser;
        public $requireSSL;
        public $title;
        public $userSource;

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
        $this->saveConfig();
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
        public function saveConfig(){
            $query = "INSERT INTO config (`key`,`value`) VALUES (:key,:value1) ON DUPLICATE KEY UPDATE `value`=:value2;";
            $stmt = $this->db->prepare($query);
            foreach($this as $key => $value){
                if(!is_array($value) AND !in_array($key, $this->hiddenFields)){
                    error_log(sprintf("Key: %s, Value: %s",$key, $value));
                    $stmt->bindValue(':key',$key);
                    if(in_array($key, $this->encodedFields)){
                        $stmt->bindValue(':value1',base64_encode($value));
                        $stmt->bindValue(':value2',base64_encode($value));
                    }else{
                        $stmt->bindValue(':value1',$value);
                        $stmt->bindValue(':value2',$value);
                    }
                    $stmt->execute();
                }
            }
        }
        public function __get($name) {
            if($this->debug){
                error_log("Property: $name");
            }
            if(in_array($name, $this->encodedFields)){
                return base64_decode($this->$name) ?? false;
            }else{
                return $this->$name ?? false;
            }
        }
}