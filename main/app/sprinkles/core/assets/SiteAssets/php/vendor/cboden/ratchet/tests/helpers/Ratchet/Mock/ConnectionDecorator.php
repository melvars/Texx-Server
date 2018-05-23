<?php

namespace Ratchet\Mock;

use Ratchet\AbstractConnectionDecorator;

class ConnectionDecorator extends AbstractConnectionDecorator
{
    public $last = array(
        'write' => ''
    , 'end' => FALSE
    );

    public function send($data) {
        $this->last[__FUNCTION__] = $data;

        $this->getConnection()->send($data);
    }

    public function close() {
        $this->last[__FUNCTION__] = TRUE;

        $this->getConnection()->close();
    }
}
