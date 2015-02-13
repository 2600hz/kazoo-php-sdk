<?php

namespace CallflowBuilder;

use CallflowBuilder\Node\AbstractNode; 

class Builder {

     /**
     *
     *
     *
     * @param array $numbers
     * @param array $patterns
     */
    public function __construct(array $numbers = array(), array $patterns = array()){
        if (!empty($numbers)){
            $this->numbers = $numbers;
        }
        if (!empty($patterns)){ 
            $this->patterns = $patterns;
        } 
    }

     /**
     *
     *
     *
     * @param \CallflowBuilder\Node\AbstractNode $root_node
     */
    public function build(AbstractNode $root_node){
        $this->flow = $root_node->build();  
        return $this; 
    }
   
     /**
     *
     *
     *
     * 
     */
    public function __toString() {
        return json_encode($this);
    }

}
