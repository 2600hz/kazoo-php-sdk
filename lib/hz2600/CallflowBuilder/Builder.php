<?php

namespace CallflowBuilder;

use CallflowBuilder\Node\AbstractNode; 

class Builder {

    public function __construct(array $numbers = array(), array $patterns = array()){
        if (isset($numbers)){
            $this->numbers = $numbers;
        }
        if (isset($patterns)){ 
            $this->patterns = $patterns;
        } 
    }

    public function build(AbstractNode $node){
        $this->flow = $node->build();  
        return $this; 
    }
   
    public function __toString() {
        return json_encode($this);
    }

}
