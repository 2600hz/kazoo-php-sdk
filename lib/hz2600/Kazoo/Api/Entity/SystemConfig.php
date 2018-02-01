<?php

namespace Kazoo\Api\Entity;
use \Kazoo\Common\Exception\InvalidArgument;

class SystemConfig extends AbstractEntity
{

	/**
	 * perform a change in system config
	 * $arg can be stdClass or key or array of key, values
	 * $value is used when $arg is $key
	 * $key used when $value is not null can be a string or an array 
	 *
	 */	
	public function change($arg, $value = null) {
		$this->reset(true);
		if(!$value) {
			if($arg instanceof \stdClass) {
				$id = $this->getId();				
				$this->setEntity($arg);
				$this->setId($id);				
			} else {
				if(!is_array($arg))
					throw new InvalidArgumentException("values is not array");
					foreach($arg as $key_values) {
						if(!is_array($key_values))
							throw new InvalidArgumentException("values item must be [key, value]");
							$this->set($key_values[0], $key_values[1]);
					};					
			}
		} else {
			if(is_array($arg)) {
				$this->set($arg, $value);
			} else {
				$this->set([$arg], $value);
			}
		};
		return $this->partialUpdate(null);
	}
}
