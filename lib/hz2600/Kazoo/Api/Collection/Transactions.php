<?php 

namespace Kazoo\Api\Collection;

class Transactions extends AbstractCollection
{
    public function current_balance() {
        $response = $this->get(array(), '/current_balance');
        $this->setCollection($response->getData());
        return $this;
    }
}
