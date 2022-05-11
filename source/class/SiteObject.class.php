<?php

class SiteObject {
        protected $properties;
    
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
            return $this->properties[$name] ?? false;
        }
        
       public function __set($name, $value) {
           $this->properties[$name]=$value;
       }
}