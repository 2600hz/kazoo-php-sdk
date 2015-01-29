<?php
namespace Kazoo\Api\Entity;

class PhoneNumber extends AbstractEntity
{ 
    public function getId() {
        $number=parent::getId();
        if (isset($number[0]) and $number[0]=="+") { 
            return substr(parent::getId(),1); 
        } else {
            return parent::getId(); 
        }
    }

    public function activate($number) {
	$id = $this->getId();
        $this->setTokenValue($this->getEntityIdName(), $id);
	$response = $this->put("","/activate");   
    }

}
