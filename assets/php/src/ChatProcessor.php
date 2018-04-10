<?php

namespace Websocket;

//use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\MessageComponentInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;

class ChatProcessor implements MessageComponentInterface
{
    protected $clients;
    private $subscriptions;
    private $users;
    private $connectedUsersNames;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->subscriptions = [];
        $this->users = [];
        $this->connectedUsersNames = [];
    }

    public function onOpen(ConnectionInterface $conn) {
        $generator = new \Nubs\RandomNameGenerator\Alliteration();
        $this->clients->attach($conn);
        $this->users[$conn->resourceId] = $conn;
        $this->connectedUsersNames[$conn->resourceId] = $generator->getName();

        echo "New connection! ({$conn->resourceId})\n";
    }

    /*public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        foreach ($this->clients as $client) {
            if ($from === $client) {
                $client->send("<b>You</b> - " . $msg);
            } else {
                $client->send("<b>" . $from->resourceId . "</b> - " . $msg);
            }
        }
    }
    */

    public function onMessage(ConnectionInterface $conn, MessageInterface $msg) {
        $data = json_decode($msg);
        switch ($data->command) {
            case "subscribe":
                $this->subscriptions[$conn->resourceId] = $data->channel;
                foreach ($this->subscriptions as $id => $channel) {
                    if ($this->subscriptions[$conn->resourceId] == $channel) {
                        if ($id === $conn->resourceId) {
                            $this->users[$id]->send("You (" . $this->connectedUsersNames[$conn->resourceId] . ") joined this group.");
                        } else {
                            $this->users[$id]->send("User (<b>" . $this->connectedUsersNames[$conn->resourceId] . "</b>) joined this group.");
                        }
                    }
                }
                break;
            case "message":
                if (isset($this->subscriptions[$conn->resourceId])) {
                    $target = $this->subscriptions[$conn->resourceId];
                    foreach ($this->subscriptions as $id => $channel) {
                        if ($channel == $target && $id == $conn->resourceId) {
                            $this->users[$id]->send("<b>You</b> - " . $data->message);
                        } else if ($channel == $target && $id != $conn->resourceId) {
                            $this->users[$id]->send("<b>" . $this->connectedUsersNames[$conn->resourceId] . "</b> - " . $data->message);
                        }
                    }
                }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
        foreach ($this->clients as $client) {
            $client->send("User <b>" . $this->connectedUsersNames[$conn->resourceId] . "</b> has disconnected");
        }
        unset($this->users[$conn->resourceId]);
        unset($this->subscriptions[$conn->resourceId]);
        unset($this->connectedUsersNames[$conn->resourceId]);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}