<?php

class SiteObject {

	public function write($array) {
		foreach($array as $key => $value){
			if(is_array($value)) {
				$this->{$key} = new SiteObject;
				$this->{$key}->write($value);
			}else{
				$this->{$key}=$value;			
			}
		}
	}
        
        public function __get($name) {
            return $this->$name ?? false;
        }
        
       public function __set($name, $value) {
           $this->$name=$value;
       }
}