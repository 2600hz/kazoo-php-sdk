<?php

namespace Kazoo\Api\Entity;


class Conference extends AbstractEntity
{
    /**
     * Dial out to endpoints and place answering endpoints in a conference
     *
     * @param stdClass $data Endpoints' information for dialing
     */

    public function dial($data) {
        $envelope = new stdClass();
        $envelope->action = "dial";
        $envelope->data = $data;

        return $this->executeDial($envelope);
    }

    public function executeDial($envelope) {
        if ($this->read_only) {
            throw new ReadOnly("The entity is read-only");
        }

        $id = $this->getId();
        $payload = json_encode($envelope);

        $this->setTokenValue($this->getEntityIdName(), $id);

        $response = $this->put($payload);

        $entity = $response->getData();
        $endpoints = new ConferenceDials();

        $endpoints->setCollection($entity);

        return $endpoints;
    }
    
    public function participantAction($id, $action) {
        if (!is_numeric($id)) {
            throw new RunTimeException("Invalid participant ID");
        }
        
        $payload = json_encode([
            "data" => [
                "action" => $action
            ]
        ]);
        
        $this->setTokenValue($this->getEntityIdName(), $this->getId());
        
        $response = $this->put($payload, "/participants/$id");
        
        return $response->getData();
    }
}
