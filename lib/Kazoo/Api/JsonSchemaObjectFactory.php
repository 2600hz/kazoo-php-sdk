<?php

namespace Kazoo\Api;

use stdClass;
use Kazoo\Api\Resources;

class JsonSchemaObjectFactory {

    public static function getNew($nounType, $schema) {
        $class = "\\Kazoo\\Api\\Resources\\".$nounType;
        $nounInstance = new $class();
        return self::transformToObject(json_decode($schema), $nounInstance);
    }
    
    private static function transformToObject($json, $accumulator){
        if(!property_exists($json, 'properties')){
            return $accumulator;
        }
        
        foreach ($json->properties as $property_name => $property_meta) {
            if($property_name !== "properties") {
                switch($property_meta->type){
                    case 'string':
                        $accumulator->$property_name = "";
                        break;
                    case 'object':
                        $accumulator->$property_name = self::transformToObject($json->properties->$property_name, new stdClass());
                        break;
                    case 'boolean':
                        $accumulator->$property_name = $property_meta->default;
                        break;
                    case 'array':
                        $accumulator->$property_name = array();
                        break;
                    case 'integer':
                        $accumulator->$property_name = null;
                        break;
                    case 'enum':
                        $accumulator->$property_name = "";
                        break;
                }
            }
        }
        
        return $accumulator;
    }
}