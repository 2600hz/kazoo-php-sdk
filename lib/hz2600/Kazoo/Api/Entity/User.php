<?php

namespace Kazoo\Api\Entity;

class User extends AbstractEntity
{
    public function quickcall($number, $options = array()) {
        $url = '/quickcall/{quickcall_number}';
        //TODO: Breaks when combining. Each consecutive option should be '&', not '?'. Foreach loop where first item has '?' ... ?
        if(!empty($options['auto_answer'])) {
            $url .= '?auto_answer=' . $options['auto_answer']; 
        }
        if(!empty($options['cid-number'])) {
            $url .= '?cid-number=' . $options['cid-number'];
        }
        if(!empty($options['cid-name'])) {
            $url .= '?cid-name=' . $options['cid-name'];
        }
        $this->setTokenValue($this->getEntityIdName(), $this->getId());
        $this->setTokenValue('quickcall_number', $number);
        $this->get(array(), $url);
    }

    /**
     * Saves the current entity, if it does not have an
     * id then it will be created.
     *
     */
    public function save($append_uri = null) {
        return parent::save($append_uri,"patch");
    }
}
