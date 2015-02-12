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
        if (isset($numbers)){
            $this->numbers = $numbers;
        }
        if (isset($patterns)){ 
            $this->patterns = $patterns;
        } 
    }

     /**
     *
     *
     *
     * @param \CallflowBuilder\Node\AbstractNode
     */
    public function build(AbstractNode $node){
        $this->flow = $node->build();  
        return $this; 
    }
   
     /**
     *
     *
     *
     * @var $data
     */
    public function __toString() {
        return json_encode($this);
    }

}
