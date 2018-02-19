<?php

namespace CallflowBuilder\Node;

class Offnet extends AbstractNode
{
    public function __construct($id = null) {
        parent::__construct();
        $this->module = "offnet";
        if (isset($id)){
            $this->id($id);
        }
    }

    public function caller_id_type($caller_id_type) {
        $this->data->caller_id_type = $caller_id_type;
        return $this;
    }

    public function ignore_early_media($ignore_early_media) {
        $this->data->ignore_early_media = $ignore_early_media;
        return $this;
    }

    public function outbound_flags($outbound_flags) {
        $this->data->outbound_flags = $outbound_flags;
        return $this;
    }

    public function use_local_resources($use_local_resources) {
        $this->data->use_local_resources = $use_local_resources;
        return $this;
    }
}

