<?php

namespace Kazoo\Api\Collection;

class Faxes extends AbstractCollection
{
    protected function getUriSnippet() {
        return "/faxes/outgoing";
    }
}
