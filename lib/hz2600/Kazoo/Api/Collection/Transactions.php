<?php 

namespace Kazoo\Api\Collection;

class Transactions extends AbstractCollection
{
    public function currentBalance() {
        $response = $this->get(array(), '/current_balance');
        $this->setCollection($response->getData());
        return $this;
    }
}
