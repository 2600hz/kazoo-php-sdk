<?php

namespace CallflowBuilder\Node;

use \stdClass;

class RingGroup extends AbstractNode
{
    public function __construct($name) {
        parent::__construct();
        $this->module = 'ring_group';
        $this->name($name);
    }

    public function name($name){
        $this->data->name = $name;
        return $this;
    }

    public function strategy($value){
        //should default to "simultaneously" on kazoo side if blank.
        if ($value) {
             $this->data->strategy = $value;
        }
        return $this;
    }

    public function timeout($timeout = null){
        $this->data->timeout = $timeout;
        return $this;
    }

    public function endpoints(array $endpoints){
        $this->data->endpoints = array();
        $absolute_timeout = 0;
        $strategy = $this->data->strategy;

        foreach ($endpoints as $id => $options){
            $options = array_merge($this->endpointDefaults(), $options);

            $endpoint = new stdClass();
            $endpoint->endpoint_type    = $options['type'];
            $endpoint->delay   = $options['delay'];
            $endpoint->timeout = $options['timeout'];
            $endpoint->id      = (string)$id;

            if ($strategy == "single") {
                $absolute_timeout = $absolute_timeout + $options['timeout'];
            } else {
                $maybe_absolute_timeout = $options['delay'] + $options['timeout'];

                if ($maybe_absolute_timeout > $absolute_timeout) {
                    $absolute_timeout = $maybe_absolute_timeout;
                }
            }
            array_push($this->data->endpoints, $endpoint);
        }
        $this->timeout($absolute_timeout);
        return $this;
    }

    //TODO: This should be based on the overall timeout
    private function endpointDefaults(){
         $defaults['delay']   = '0';
         $defaults['timeout'] = '20';
         return $defaults;
    }
}
