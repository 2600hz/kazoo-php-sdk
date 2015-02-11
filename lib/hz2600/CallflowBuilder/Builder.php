<?php

namespace CallflowBuilder;

use CallflowBuilder\Node\AbstractNode; 

class Builder {
    
    public static function build(AbstractNode $node){
         return $node->build();
    }

}
