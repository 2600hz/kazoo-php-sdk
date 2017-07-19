<?php
namespace Kazoo\Api\Entity;

class ServicePlan extends AbstractEntity
{
    public function current() {
        $this->read_only = TRUE;
        return $this->fetch('/current');
    }

    protected function getUriSnippet() {
        $collectionName = $this->getCollectionName();
        $entityId = $this->getId();

        if (empty($entityId)) {
            return "/$collectionName";
        }

        $collectionName = $this->getCollectionName();
        $entityIdName = $this->getEntityIdName();
        return "/$collectionName/{{$entityIdName}}";
    }
}
