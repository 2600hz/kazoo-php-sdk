<?php

namespace Kazoo\Api\Data\Entity;

use Kazoo\Api\Data\AbstractEntity;

class Media extends AbstractEntity {

    protected static $_schema_name = "media.json";
    protected static $_callflow_module = "media";
    
    private $_file_path = "";

    public function initDefaultValues() {
        unset($this->source_id);
        unset($this->source_type);
        unset($this->content_length);
        unset($this->content_type);
        $this->media_type ="wav";
        $this->description = "C:\\fakepath\\media.mp3";
    }
    
    public function setUploadFilePath($file_path){
        $this->_file_path = $file_path;
    }

    public function getCallflowDefaultData() {
        $this->_default_callflow_data->id = $this->id;
        return $this->_default_callflow_data;
    }
    
    /**
     * 
     * @param string $name
     * @param null|array $arguments
     * @return \Kazoo\Api\Data\AbstractEntity
     */
    public function __call($name, $arguments) {
        switch (strtolower($name)) {
            case 'upload':
                
                $pathinfo = pathinfo($this->_file_path);

                $base64Data = base64_encode(file_get_contents($this->_file_path));

                switch(strtolower($pathinfo['extension'])){
                    case 'x-wav':
                    case 'wav':
                        $base64 = "data:audio/x-wav;base64," . $base64Data;
                        break;
                    case 'mpeg':
                    case 'mpeg3':
                    case 'mp3':
                        $base64 = "data:audio/mp3;base64," . $base64Data;
                        break;
                    case 'ogg':
                        $base64 = "data:audio/ogg;base64," . $base64Data;
                        break;
                    default:
                        throw new \Kazoo\Exception\RuntimeException("Invalid media extension!");
                        break;
                }
                
                $headers = array("Content-Type" => "application/x-base64", "Accept" => "application/json");
                $this->_client->postRaw($this->_uri . "/raw", $base64, $headers);
            case 'save':
                if(strlen($this->id) > 0){
                    $result = $this->_client->post($this->_uri, $this->getData());
                } else {
                    $result = $this->_client->put($this->_uri, $this->getData());
                }
                $this->updateFromResult($result->data);
                break;
            case 'delete':
                return $this->_client->delete($this->_uri);
                break;
        }

        return $this;
    }

}