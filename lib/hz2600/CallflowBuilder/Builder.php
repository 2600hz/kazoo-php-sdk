<?php

namespace CallflowBuilder;

//require_once dirname(__FILE__) . "/../../vendor/autoload.php";

use CallflowBuilder\Node\AbstractNode; 

class Builder {
    
    public static function build(AbstractNode $node){
         return $node->build();
    }

}
