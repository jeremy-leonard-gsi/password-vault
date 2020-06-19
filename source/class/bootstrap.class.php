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

class bsContainer {
	public const CONTAINER='container';
	public const CONTAINER_SM='container-sm';
	public const CONTAINER_MD='container-md';
	public const CONTAINER_LG='container-lg';
	public const CONTAINER_XL='container-XL';
	public const CONTAINER_FLUID='container-fluid';
	
	protected $type;
	protected $content;	
	
	public function __construct($type=null) {
		if(is_null($type)) {
			$this->type=bsContainer::CONTAINER;
		}else{
			$this->type=$type;		
		}
			
	}

	public function setType($type) {
		$this->type=$type;
	}	
	
	public function addContent($content) {	
		$this->content .= $content;
	}
	public function show() {
		$output = "<div class=\"".$this->type."\">";
		$output .= $this->content;
		$output .= "</div>";
		return $output;
	}
}

class bsModal {
	public $title,$body,$footer,$size,$close,$fade,$isStatic,$scrollable,$centered;
	protected $id;
	
	public function __construct($id, $title=null,$body=null,$footer=null,$size='',$close=true,$fade=true,$isStatic=false,$scrollable=false,$centered=false) {
		$this->id=$id;
		$this->title=$title;
		$this->body=$body;
		$this->footer=$footer;
		$this->size=$size;
		$this->close=$close;
		$this->fade=$fade;
		$this->isStatic=$isStatic;
		$this->scrollable=$scrollable;
		$this->centered=$centered;
	}
	
	public function show() {
		$output = "<div class=\"modal";
		if($this->fade) $output .= " fade";
		$output .= "\" id=\"$this->id\" tabindex=-1 role=\"dialog\"";
		if($this->isStatic) $output .= " data-backdrop=\"static\" data-keyboard=\"false\"";
		$output .= "aria-labelledby=\"".$this->id."Label\" aria-hidden=\"true\">";
		$output .= "<div class=\"modal-dialog";
				switch($this->size) {
			case 'sm':
				$output .= " modal-sm";
				break;
			case 'lg':
				$output .= " modal-lg";
				break;
			case 'xl':
				$output .= " modal-xl";
				break;
		}
		if($this->scrollable) $output .= " modal-dialog-scrollable";
		if($this->centered) $output .= " modal-dialog-centered";
		$output .= "\">";
		$output .= "<div class=\"modal-content\">";
		$output .= "<div class=\"modal-header\">";
		$output .= "<h5 class=\"modal-title\" id=\"".$this->id."Label\">";
		$output .= $this->title;
		$output .= "</h5>";
		if($this->close) {
			$output .= "<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"close\">";
			$output .= "<span aria-hidden=\"true\">&times;</span>";
			$output .= "</button>";
		}
		$output .= "</div>";
		$output .= "<div class=\"modal-body\">";
		$output .= $this->body;
		$output .= "</div>";
		$output .= "<div class=\"modal-footer\">";
		$output .= $this->footer;
		$output .= "</div>";
		$output .= "</div>";
		$output .= "</div>";
		$output .= "</div>";
		return $output;
	}
}
?>