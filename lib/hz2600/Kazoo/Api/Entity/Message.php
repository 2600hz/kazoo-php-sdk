<?php
namespace Kazoo\Api\Entity;

class Message extends Media
{

	protected function getCollectionName(){
        return "messages";
    }

}
