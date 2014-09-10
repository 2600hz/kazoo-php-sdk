<?php

namespace Kazoo\Api\Resource;
use Kazoo\Api\AbstractResource;

/**
 *
 */
class Media extends AbstractResource {

    protected static $_entity_class = "Kazoo\\Api\\Data\\Entity\\Media";
    protected static $_entity_collection_class = "Kazoo\\Api\\Data\\Collection\\MediaCollection";

    public function __call($name, $arguments) {

        switch (strtolower($name)) {
            case 'raw':
                if (empty($arguments[0])) return "";
                $resource_id = $arguments[0];

                $authToken = $this->_client->getAuthToken();

                $uri = $this->_client->getTokenizedUri($this->_uri) . "/" . urlencode($resource_id) . "/raw?auth_token=" . urlencode($authToken->getToken());

                if (!empty($arguments[1]))
                    return file_get_contents($uri);
                else
                    return $uri;

                break;
            default:
                return parent::__call($name, $arguments);
        }
    }
}
