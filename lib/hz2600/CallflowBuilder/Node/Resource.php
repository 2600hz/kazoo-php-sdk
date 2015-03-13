<?php

namespace CallflowBuilder\Node; 

class Resource extends AbstractNode
{
    public function __construct($id = NULL) {
        parent::__construct();
        $this->module = "resources";
        if (isset($id)){
           $this->huntAccountId($id);
        }
        else {
           $this->useLocalResources(FALSE);
        }   
    }

    public function toDid($did){
        $this->data->to_did = $did;
        return $this;  
    }

    public function media($media_id){
        $this->data->media = $media_id; 
        return $this; 
    }
  
    public function ringback($ringback_id){
        $this->data->ringback = $ringback_id;
        return $this; 
    } 

    public function formatFromDid($value){
        $this->data->format_from_did = $value;
        return $this;  
    }

    public function timeout($value){
        $this->data->timeout = $value; 
        return $this; 
    }

    public function doNotNormalize($value){
        $this->data->do_not_normalize = $value; 
        return $this; 
    }

    public function bypassE164($value){
        $this->data->bypass_e164 = $value; 
        return $this; 
    }

    public function fromUriRealm($realm){
        $this->data->from_uri_realm = $realm;
        return $this; 
    }

    public function callerIdType($type){
        $this->data->caller_id_type = $type;
        return $this; 
    }

    public function useLocalResources($value){
        $this->data->use_local_resources = $value;
        return $this; 
    }
 
    public function huntAccountId($id){
        $this->data->hunt_account_id = $id; 
        return $this; 
    }

    public function emitAccountId($id){
        $this->data->emit_account_id = $id; 
        return $this; 
    }

    public function customSipHeaders(array $headers){
        $this->data->custom_sip_headers = $headers;
        return $this; 
    }   

    public function ignoreEarlyMedia($value){
        $this->data->ignore_early_media = $value; 
        return $this; 
    }   
 
    public function outboundFlags($flags){
        $this->data->outbound_flags = $flags;
        return $this; 
    }

}
