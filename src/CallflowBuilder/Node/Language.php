<?php

namespace CallflowBuilder\Node; 

class Language extends AbstractNode
{
    public function __construct($language) {
        parent::__construct();
        $this->module = "language";
        $this->data->language = $language;
    }
}


