<?php

namespace Websocket;

//use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\MessageComponentInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Nubs\RandomNameGenerator\Alliteration;

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
        $generator = new Alliteration();
        $this->clients->attach($conn);
        $this->users[$conn->resourceId] = $conn;
        $this->connectedUsersNames[$conn->resourceId] = $generator->getName();
    }

    public function onMessage(ConnectionInterface $conn, MessageInterface $msg) {
        $data = json_decode($msg);
        switch ($data->ClientMessageType) {
            case "Subscribe":
                $this->subscriptions[$conn->resourceId] = $data->Channel;
                foreach ($this->subscriptions as $id => $channel) {
                    if ($this->subscriptions[$conn->resourceId] == $channel) {
                        $MessageObject = new \stdClass();
                        $MessageObject->ServerMessage = TRUE;
                        $MessageObject->ServerMessageType = "GroupJoin";
                        $MessageObject->GroupName = $channel;
                        $MessageObject->Username = $this->connectedUsersNames[$conn->resourceId];
                        if ($id === $conn->resourceId) {
                            $MessageObject->WasHimself = TRUE;
                        } else {
                            $MessageObject->WasHimself = FALSE;
                        }
                        $MessageJson = json_encode($MessageObject, TRUE);
                        $this->users[$id]->send($MessageJson);
                    }
                }
                break;
            case "Message":
                if (isset($this->subscriptions[$conn->resourceId])) {
                    $target = $this->subscriptions[$conn->resourceId];
                    foreach ($this->subscriptions as $id => $channel) {
                        if ($channel == $target) {
                            $MessageObject = new \stdClass();
                            $MessageObject->ServerMessage = FALSE;
                            $MessageObject->GroupName = $channel;
                            $MessageObject->Username = $this->connectedUsersNames[$conn->resourceId];
                            $MessageObject->Message = htmlspecialchars($data->Message);
                            if ($id === $conn->resourceId) {
                                $MessageObject->WasHimself = TRUE;
                            } else {
                                $MessageObject->WasHimself = FALSE;
                            }
                            $MessageJson = json_encode($MessageObject, TRUE);
                            $this->users[$id]->send($MessageJson);
                        }
                    }
                }
                break;
            case "TypingState":
                if (isset($this->subscriptions[$conn->resourceId])) {
                    $target = $this->subscriptions[$conn->resourceId];
                    foreach ($this->subscriptions as $id => $channel) {
                        if ($channel == $target) {
                            $MessageObject = new \stdClass();
                            $MessageObject->ServerMessage = TRUE;
                            $MessageObject->ServerMessageType = "TypingState";
                            $MessageObject->GroupName = $channel;
                            $MessageObject->Username = $this->connectedUsersNames[$conn->resourceId];
                            $MessageObject->State = $data->State;
                            if ($id === $conn->resourceId) {
                                $MessageObject->WasHimself = TRUE;
                            } else {
                                $MessageObject->WasHimself = FALSE;
                            }
                            $MessageJson = json_encode($MessageObject, TRUE);
                            $this->users[$id]->send($MessageJson);
                        }
                    }
                }
                break;
            case "Verify":
                $headerCookies = explode('; ', $data->Cookie);
                $cookies = array();
                foreach ($headerCookies as $headerCookie) {
                    list($key, $val) = explode('=', $headerCookie, 2);
                    $cookies[$key] = $val;
                }
                $UserSessionKey = $cookies["uf4"];
                $AccessToken = file_get_contents("/AccessToken.txt"); // SECRET
                $KeyVerifierCode = $this->getHttpCode("https://beam-messenger.de/wormhole/" . $AccessToken . "/verify/" . $data->UserID . "/" . $UserSessionKey);
                if ($KeyVerifierCode === 200) {
                    echo "Access granted";
                } else {
                    echo "Access denied";
                }
                break;
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        foreach ($this->clients as $client) {
            if (isset($this->subscriptions[$conn->resourceId])) {
                $target = $this->subscriptions[$conn->resourceId];
                foreach ($this->subscriptions as $id => $channel) {
                    if ($channel == $target) {
                        $MessageObject = new \stdClass();
                        $MessageObject->ServerMessage = TRUE;
                        $MessageObject->ServerMessageType = "UserDisconnect";
                        $MessageObject->Username = $this->connectedUsersNames[$conn->resourceId];
                        $MessageJson = json_encode($MessageObject, TRUE);
                        $this->users[$id]->send($MessageJson);
                    }
                }
            }
        }
        unset($this->users[$conn->resourceId]);
        unset($this->subscriptions[$conn->resourceId]);
        unset($this->connectedUsersNames[$conn->resourceId]);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    public function getHttpCode($domain) {
        $headers = get_headers($domain);
        return substr($headers[0], 9, 3);
    }
}