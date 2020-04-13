<?php

class bsAlert {
	
	protected $message,$context,$dismiss,$id;
	
	public const PRIMARY = 'alert-primary';
	public const SECONDARY = "alert-secondary";
	public const SUCCESS = "alert-success";
	public const DANGER = "alert-danger";
	public const WARNING = "alert-warning";
	public const INFO = "alert-info";
	public const LIGHT = "alert-light";
	public const DARK = "alert-dark";

 	public function __construct($message=null,$context=bsAlert::LIGHT,$dismiss=false,$id=null){
		$this->message=$message;
 		$this->context=$context;
 		$this->dismiss=$dismiss;
		$this->id=$id;
 	}
 	
	public function setMessage($message) {
		$this->message=$message;
	} 	

	public function getMessage() {
		return $this->message;
	} 	
	
	public function setContext($context){
		$this->context=$context;	
	}
	
	public function setDismiss($dismiss) {
		$this->dismiss=$dismiss;
	}
	
	public function setId($id) {
		$this->id=$id;
	}
 	
 	public function show() {
 		$output = "<div ";
 		if(!(is_null($this->id))) {
 			$output .= "id=\"".$this->id."\" ";
 		}
 		$output .= "class=\"alert ".$this->context;
 		if($this->dismiss){
 			$output .=" alert-dismissible fade show";
 			$this->message .= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">\n<span aria-hidden=\"true\">&times;</span>\n</button>\n";
 		} 
 		$output .="\" role=\"alert\">";
 		$output .= $this->message;
 		$output .= "</div>";
 		return $output;
 	}

}
?>