<?php

namespace Kazoo\Api\Entity;


class Braintree extends AbstractEntity
{
	public function credit() {
        $response = $this->get(array(), '/credits');
        $entity = $response->getData();
        $this->setEntity($entity);        
        return $this;		
	}

	public function customer() {
        $response = $this->get(array(), '/customer');
        $entity = $response->getData();
        $this->setEntity($entity);        
        return $this;		
	}

    protected function getUriSnippet() {
        return "/braintree";
    }
}    
