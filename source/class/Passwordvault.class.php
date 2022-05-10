<?php

class Passwordvault {
    
    protected $config;
    private $secret;
    private $db;

    public function __construct($config) {
            $this->config = $config;
            $this->secret = $config->pwvSecret;
            $this->db = new PDO($config->pwvDSN,$config->pwvUser,base64_decode($config->pwvPassword));
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	
	public function searchCompanies($companyName) {
		$query = "SELECT * FROM companies WHERE companyName like :companyName;";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':companyName',$companyName,PDO::PARAM_STR);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);		
	}	
	
	public function getCompanies() {
		$query = "SELECT * FROM companies;";
		$stmt = $this->db->prepare($query);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}	
	
	public function getAccounts($companyId=null) {
            if(in_array($this->config->globalAdminGroupDN, $_SESSION['groups'])){            
		if(is_null($companyId)) {
			$query = "SELECT DISTINCT accounts.*,passwords.* FROM accounts LEFT JOIN passwords ON accounts.accountId=passwords.accountId AND passwordActive=1 WHERE `accountDeleted` = false ORDER BY system,accountName;";
			$stmt = $this->db->prepare($query);
		}else{
			$query = "SELECT DISTINCT accounts.*,passwords.* FROM accounts LEFT JOIN passwords ON accounts.accountId=passwords.accountId AND passwordActive=1 WHERE `accountDeleted` = false AND `companyId` = :companyId ORDER BY accountName;";
			$stmt = $this->db->prepare($query);
			$stmt->bindValue(':companyId',$companyId,PDO::PARAM_INT);
		}
            }else{
		if(is_null($companyId)) {
			$query = "SELECT DISTINCT accounts.*,passwords.* FROM accounts LEFT JOIN passwords ON accounts.accountId=passwords.accountId AND passwordActive=1 LEFT JOIN acls ON accounts.accountId=acls.accountId  WHERE `accountDeleted` = false AND (";
                        foreach($_SESSION['groups'] as $key => $group){
                            $groups[] = "acls.group=:group$key";
                        }
                        $query .= implode(' OR ', $groups);
                        $query .= ") ";
                        $query .= "ORDER BY system,accountName;";
			$stmt = $this->db->prepare($query);
                        foreach($_SESSION['groups'] as $key => $group){
                            $stmt->bindValue(":group$key",$group);
                        }
		}else{
			$query = "SELECT DISTINCT accounts.*,passwords.* FROM accounts LEFT JOIN passwords ON accounts.accountId=passwords.accountId AND passwordActive=1 LEFT JOIN acls ON accounts.accountId=acls.accountId WHERE `accountDeleted` = false AND `companyId` = :companyId AND (";
                        foreach($_SESSION['groups'] as $key => $group){
                            $groups[] = "acls.group=:group$key";
                        }
                        $query .= implode(' OR ', $groups);
                        $query .= ") ";
                        $query .= "ORDER BY system,accountName;";
			$stmt = $this->db->prepare($query);
                        foreach($_SESSION['groups'] as $key => $group){
                            $stmt->bindValue(":group$key",$group);
                        }
			$stmt->bindValue(':companyId',$companyId,PDO::PARAM_INT);
		}                
            }
		if($this->config->debug){
                    error_log($stmt->queryString);
                }
                $stmt->execute();
		$accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach($accounts as $key => $account){
			if(substr($account["password"],0,4)=='enc:') {
				$accounts[$key]['password']=htmlentities($this->pwvDecrypt(substr($account['password'],4),$this->secret));		
			}else{
				$accounts[$key]['password']=htmlentities(base64_decode($account['password']));
				$this->encryptExistingPassword($account['passwordId'],base64_decode($account['password']));			
			}		
		}
		return $accounts;
		
	}
	public function getAccountInfo($accountId) {
            if(in_array($this->config->globalAdminGroupDN, $_SESSION['groups'])){            
		$query = "SELECT DISTINCT accounts.*,passwords.* FROM accounts LEFT JOIN passwords ON accounts.accountId=passwords.accountId AND passwordActive=1 WHERE accounts.accountId=:accountId;";
                $stmt = $this->db->prepare($query);
            }else{
		$query = "SELECT DISTINCT accounts.*,passwords.* FROM accounts LEFT JOIN passwords ON accounts.accountId=passwords.accountId AND passwordActive=1 LEFT JOIN acls ON accounts.accountId=acls.accountId WHERE accounts.accountId=:accountId AND (";
                foreach($_SESSION['groups'] as $key => $group){
                    $groups[] = "acls.group=:group$key";
                }
                $query .= implode(' OR ', $groups);
                $query .= ");";
                $stmt = $this->db->prepare($query);
                foreach($_SESSION['groups'] as $key => $group){
                    $stmt->bindValue(":group$key",$group);
                }
            }
            $stmt->bindValue(':accountId',$accountId,PDO::PARAM_INT);
            if($this->config->debug){
                error_log($stmt->queryString);
            }
            $stmt->execute();
            $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($this->config->debug){
                error_log($stmt->rowCount());
            }
            foreach($accounts as $key => $account){
                if(substr($account['password'],0,4)=='enc:') {
                    $accounts[$key]['password']=htmlentities($this->pwvDecrypt(substr($account['password'],4),$this->secret));
                }else{
                    $accounts[$key]['password']=htmlentities(base64_decode($account['password']));
                    $this->encryptExistingPassword($account['passwordId'],base64_decode($account['password']));			
                }
                $accounts[$key]['accountNotes']=base64_decode($accounts[$key]['accountNotes']);		
            }
            $query = "SELECT `group` FROM acls WHERE accountId=:accountId;";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":accountId", $accountId);
            if($this->config->debug){
                error_log($stmt->queryString);
            }            
            $stmt->execute();
            $groups = $stmt->fetchAll();
            foreach($groups as $group){
                $accounts[0]['acls'][]=$group['group'];
            }
            $accounts[0]['userGroups']=$_SESSION['groups'];
            $accounts[0]['configGroups']=explode(';',$this->config->groupDNs);
            return $accounts;
	}
	public function getCurrentPassword($accountId) {
            if(in_array($this->config->globalAdminGroupDN, $_SESSION['groups'])){            
		$query = "SELECT * FROM `passwords` WHERE `passwords`.accountid=:accountid AND passwordActive=true;";
                $stmt = $this->db->prepare($query);
            }else{
		$query = "SELECT passwords.* FROM `passwords` LEFT JOIN `acls` ON `acls`.`accountId`=`passwords`.`accountId` WHERE `passwords`.accountid=:accountid AND passwordActive=true AND (";
                foreach($_SESSION['groups'] as $key => $group){
                    $groups[] = "acls.group=:group$key";
                }
                $query .= implode(' OR ', $groups);
                $query .= ");";
                $stmt = $this->db->prepare($query);
                foreach($_SESSION['groups'] as $key => $group){
                    $stmt->bindValue(":group$key",$group);
                }
            }
            $stmt->bindValue(':accountid',$accountId,PDO::PARAM_INT);
            if($this->config->debug){
                error_log($stmt->queryString);
            }
            $stmt->execute();
            $passwords = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($passwords as $key => $password){
                if(substr($password['password'],0,4)=='enc:') {
                    $passwords[$key]['password']=htmlentities($this->pwvDecrypt(substr($password['password'],4),$this->secret));		
                }else{
                    $passwords[$key]['password']=htmlentities(base64_decode($password['password']));
                    $this->encryptExistingPassword($password['passwordId'],base64_decode($password['password']));			
                }		
            }
            return $passwords[0]['password']; 	
	}

	public function getPasswordHistory($accountId) {
            if(in_array($this->config->globalAdminGroupDN, $_SESSION['groups'])){            
		$query = "SELECT * FROM `passwords` WHERE accountid=:accountid ORDER BY passwordCreated DESC;";
                $stmt = $this->db->prepare($query);
            }else{
		$query = "SELECT passwords.* FROM `passwords` LEFT JOIN acls ON `passwords`.accountId=acls.accountId WHERE `passwords`.accountid=:accountid AND (";
                foreach($_SESSION['groups'] as $key => $group){
                    $groups[] = "acls.group=:group$key";
                }
                $query .= implode(' OR ', $groups);
                $query .= ") ORDER BY passwordCreated DESC;";
                $stmt = $this->db->prepare($query);
                foreach($_SESSION['groups'] as $key => $group){
                    $stmt->bindValue(":group$key",$group);
                }
            }
            if($this->config->debug){
                error_log($stmt->queryString);
            }
            $stmt->bindValue(':accountid',$accountId,PDO::PARAM_INT);
            $stmt->execute();
            $passwords = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($passwords as $key => $password){
                if(substr($password['password'],0,4)=='enc:') {
                    $passwords[$key]['password']=htmlentities($this->pwvDecrypt(substr($password['password'],4),$this->secret));		
                }else{
                    $passwords[$key]['password']=htmlentities(base64_decode($password['password']));
                    $this->encryptExistingPassword($password['passwordId'],base64_decode($password['password']));			
                }		
            }
            return $passwords; 	
	}
	
	public function addAccountTP($companyId, $system, $accountName, $accountNotes, $password, $user, $url) {
		$query = "INSERT INTO accounts (`companyId`,`system`,`accountName`,`accountCreatedBy`,`accountModified`,`accountModifiedBy`,`accountNotes`,`url`) VALUES (:companyId,:system,:accountName,:accountCreatedBy,now(),:accountModifiedBy,:accountNotes,:url);";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':companyId',$companyId,PDO::PARAM_INT);
		$stmt->bindValue('system',$system,PDO::PARAM_STR);
		$stmt->bindValue(':accountName',$accountName,PDO::PARAM_STR);
		$stmt->bindValue(':accountCreatedBy',$user,PDO::PARAM_STR);
		$stmt->bindValue(':accountModifiedBy',$user,PDO::PARAM_STR);
		$stmt->bindValue(':accountNotes',$accountNotes,PDO::PARAM_STR);
		$stmt->bindValue(':url',$url,PDO::PARAM_STR);
		$stmt->execute();
		$accountId=$this->db->lastInsertId();
		$this->addPassword($accountId,$password,$user);
	}
	public function addAccount($system, $accountName, $accountNotes, $password, $user, $url) {
            $query = "INSERT INTO accounts (`system`,`accountName`,`accountCreatedBy`,`accountModified`,`accountModifiedBy`,`accountNotes`,`url`) VALUES (:system,:accountName,:accountCreatedBy,now(),:accountModifiedBy,:accountNotes,:url);";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue('system',$system,PDO::PARAM_STR);
            $stmt->bindValue(':accountName',$accountName,PDO::PARAM_STR);
            $stmt->bindValue(':accountCreatedBy',$user,PDO::PARAM_STR);
            $stmt->bindValue(':accountModifiedBy',$user,PDO::PARAM_STR);
            $stmt->bindValue(':accountNotes',base64_encode($accountNotes),PDO::PARAM_STR);
            $stmt->bindValue(':url',$url,PDO::PARAM_STR);
            if($this->config->debug){
                error_log($stmt->queryString);
            }
            $stmt->execute();
            $accountId=$this->db->lastInsertId();
            $this->addPassword($accountId,$password,$user);
	}
	public function updateAccount($accountId, $system, $accountName, $accountNotes, $password, $user, $url, $acls) {
            $query = "UPDATE `accounts` SET `system` = :system, `accountName` = :accountName, `accountNotes` = :accountNotes, `accountModifiedBy` = :accountModifiedBy, `accountModified` = now(), `url` = :url WHERE accounts.`accountId` = :accountId;";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':accountId',$accountId,PDO::PARAM_INT);
            $stmt->bindValue('system',$system,PDO::PARAM_STR);
            $stmt->bindValue(':accountName',$accountName,PDO::PARAM_STR);
            $stmt->bindValue(':accountModifiedBy',$user,PDO::PARAM_STR);
            $stmt->bindValue(':accountNotes',base64_encode($accountNotes),PDO::PARAM_STR);
            $stmt->bindValue(':url',$url,PDO::PARAM_STR);
            if($this->config->debug){
                error_log($stmt->queryString);
            }
            $stmt->execute();
            $this->addPassword($accountId,$password,$user);
	}			
	
	public function addPassword($accountId,$password,$user){
            if($password!=$this->getCurrentPassword($accountId)){
                $query = "UPDATE `passwords` SET passwordActive=false WHERE accountId=:accountId;";
                $stmt = $this->db->prepare($query);
                $stmt->bindValue(':accountId',$accountId,PDO::PARAM_INT);
                if($this->config->debug){
                    error_log($stmt->queryString);
                }
                $stmt->execute();
                $query = "INSERT INTO `passwords` (`accountId`,`password`,`passwordCreatedBy`,`passwordActive`) VALUES (:accountId, :password, :user, 1);";
                $stmt = $this->db->prepare($query);
                $stmt->bindValue(':accountId',$accountId,PDO::PARAM_INT);
                $stmt->bindValue(':password','enc:'.$this->pwvEncrypt($password,$this->secret),PDO::PARAM_STR);
                $stmt->bindValue(':user',$user,PDO::PARAM_STR);
                if($this->config->debug){
                    error_log($stmt->queryString);
                }
                $stmt->execute();
            }
	}	

	public function deleteAccount($accountId) {
            $query = "UPDATE `accounts` SET `accountDeleted`=true WHERE accountId=:accountId;";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':accountId',$accountId,PDO::PARAM_INT);
            if($this->config->debug){
                error_log($stmt->queryString);
            }
            $stmt->execute();
	}
	
	
	public function encryptExistingPassword($passwordId,$password) {
		$encryptedPassword = 'enc:'.$this->pwvEncrypt($password,$this->secret);
		$query = "UPDATE `passwords` SET password=:password WHERE passwordId=:passwordId;";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':password',$encryptedPassword,PDO::PARAM_STR);
		$stmt->bindValue(':passwordId',$passwordId,PDO::PARAM_INT);
		$stmt->execute();
	}

	// Encryption/Decryption functions using openssl and aes-254-cbc encryption
	public function pwvEncrypt($text,$secret,$method="aes-256-cbc") {
		$salt='';		
		for($c=0;$c<8;$c++){
			$salt .=	chr(random_int(32,126));	
		}
		$key = hash("sha256",$secret);
		// IV must be exact 16 chars (128 bit)
		$iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);
		return base64_encode(openssl_encrypt($salt.$text, $method, $key, OPENSSL_RAW_DATA, $iv));
	}

	public function pwvDecrypt($text,$secret,$method="aes-256-cbc") {
		$key = hash("sha256",$secret);
		// IV must be exact 16 chars (128 bit)
		$iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);
		return substr(openssl_decrypt(base64_decode($text), $method, $key, OPENSSL_RAW_DATA, $iv),8);
	}


	public function importCSV($file) {
		$fh = fopen($file['tmp_name'], 'r');
		$count = 0;
		while(($row = fgetcsv($fh)) !== false){
			$count++;
			if($count > 1 ) {
				$this->addAccount($row[1],$row[0],$row[3],$row[2],$_SESSION['username'],$row[4]);
			}
		}
		fclose($fh);
	}
	
	public function exportCSV($companyId=null) {
		if($companyId=='null') $companyId=null;
		$accounts = $this->getAccounts($companyId);
		$output = '"User Name","System","Password","Notes","URL"';
		$output .= "\r\n";
		foreach($accounts as $account){
			$output .= '"'.$account['accountName'].'","'.$account['system'].'","'.$account['password'].'","'.base64_decode($account['accountNotes']).'","'.$account['url'].'"'."\r\n";
		}
		return $output;
	}
}
/*

Migrate accounts:

INSERT INTO pwvdb.accounts (accountId
	,system
	,accountName
	,accountCreatedBy
	,accountModifiedBy
	,accountCreated
	,accountModified
	,accountNotes) 
SELECT db_pwv.tbl_users.ID as accountId
	, db_pwv.tbl_companies.companyname AS system
	, name as accountName
	, changedby AS accountCreatedBy
	, changedby AS accountModifiedBy
	, datechanged AS accountCreated
	, datechanged as accountModified
	, notes as AccountNotes
	 FROM db_pwv.tbl_users
	 	LEFT JOIN db_pwv.tbl_companies ON db_pwv.tbl_users.companyID=db_pwv.tbl_companies.ID;

Migrate Passwords:

INSERT INTO pwvdb.passwords (accountId,password,passwordCreated,passwordCreatedBy,passwordActive) SELECT ID as accountId,password,datechanged as passwordCreated, changedby as passwordCreatedBy, 1 as passwordActive FROM DB_PWV.tbl_users;


*/
?>
