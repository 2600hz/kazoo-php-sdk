<?php

namespace CallflowBuilder;

require_once dirname(__FILE__) . "/../../vendor/autoload.php";

use CallflowBuilder\Node\AbstractNode; 

class Builder {

    public function __construct(array $numbers, array $patterns = array()){
        $this->numbers = $numbers; 
        $this->patterns = $patterns; 
    }

    public function flow (AbstractNode $node){
        $this->flow = $node->build();  
 
    }


    public function contactList($exclude = FALSE){
        $contact_list = new stdClass();
        $contact_list = $exclude; 
	$this->data->contact_list = $contact_list;
    }        
   
     public function __toString() {
          return json_encode($this);
     }


}
