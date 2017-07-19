<?php

namespace CallflowBuilder\Node; 

class Pivot extends AbstractNode
{
    public function __construct() {
        parent::__construct();
        $this->module = "pivot";
   }

   public function method($method){
        $this->data->method = $method; 
        return $this; 
   }

   public function req_timeout($timeout){
        $this->data->req_timeout = $timeout; 
        return $this; 
   }

   public function req_format($format){
        $this->data->req_format = $format; 
        return $this; 
   }

   public function voice_url($url){
        $this->data->voice_url = $url; 
        return $this; 
   }
   
}
