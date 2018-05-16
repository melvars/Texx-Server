<?php

namespace Websocket;

//use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\MessageComponentInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;

class ChatProcessor implements MessageComponentInterface
{
    protected $clients;
    private $channels;
    private $users;
    private $userID;
    private $userInfo;
    private $verifiedUsers;
    private $ReceiversId;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->channels = [];
        $this->users = []; // TEMPORARY WEBSOCKET USER
        $this->userID = []; // USER ID WHICH IS DECLARED IN DB
        $this->userInfo = []; // JSON CONTAINING ALL INFO OF USER FROM DB
        $this->ReceiversId = [];
        $this->verifiedUsers = [];
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        $this->users[$conn->resourceId] = $conn;
    }

    public function onMessage(ConnectionInterface $conn, MessageInterface $msg) {
        $data = json_decode($msg);
        switch ($data->ClientMessageType) {
            case "Verify": // USER WANTS TO GET VERIFIED
                $headerCookies = explode('; ', $data->Cookie);
                $cookies = array();
                foreach ($headerCookies as $headerCookie) {
                    list($key, $val) = explode('=', $headerCookie, 2);
                    $cookies[$key] = $val;
                }
                $UserSessionKey = $cookies["uf4"];
                $KeyVerifierCode = $this->getHttpCode("https://beam-messenger.de/wormhole/" . file("/AccessToken.txt", FILE_IGNORE_NEW_LINES)["0"] . "/verify/" . $data->UserID . "/" . $UserSessionKey);
                if ($KeyVerifierCode === "200") { // VERIFICATION SUCCEEDED
                    $this->userInfo[$conn->resourceId] = json_decode(file_get_contents("https://beam-messenger.de/wormhole/" . file("/AccessToken.txt", FILE_IGNORE_NEW_LINES)["0"] . "/user/" . $data->UserID));
                    $this->userID[$conn->resourceId] = $this->userInfo[$conn->resourceId]->id;
                    if (isset($this->userInfo[$conn->resourceId]->id)) { // USER FOUND
                        $MessageObject = new \stdClass();
                        $MessageObject->ServerMessage = TRUE;
                        $MessageObject->ServerMessageType = "Verify";
                        $MessageObject->Granted = TRUE;
                        if (isset($this->userInfo[array_flip($this->channels)[$this->channels[$conn->resourceId]]]->user_name)) $MessageObject->Receiver = $this->userInfo[array_flip($this->channels)[$this->channels[$conn->resourceId]]]->user_name;
                        $this->verifiedUsers[$conn->resourceId] = TRUE;
                        $this->users[$conn->resourceId]->send(json_encode($MessageObject, TRUE));
                    } else {
                        $MessageObject = new \stdClass();
                        $MessageObject->ServerMessage = TRUE;
                        $MessageObject->ServerMessageType = "Verify";
                        $MessageObject->Granted = FALSE;
                        if (isset($this->userInfo[array_flip($this->channels)[$this->channels[$conn->resourceId]]]->user_name)) $MessageObject->Receiver = $this->userInfo[array_flip($this->channels)[$this->channels[$conn->resourceId]]]->user_name;
                        $this->verifiedUsers[$conn->resourceId] = FALSE;
                        $this->users[$conn->resourceId]->send(json_encode($MessageObject, TRUE));
                        $this->onClose($conn);
                    }
                } else {
                    $MessageObject = new \stdClass();
                    $MessageObject->ServerMessage = TRUE;
                    $MessageObject->ServerMessageType = "Verify";
                    $MessageObject->Granted = FALSE;
                    if (isset($this->userInfo[array_flip($this->channels)[$this->channels[$conn->resourceId]]]->user_name)) $MessageObject->Receiver = $this->userInfo[array_flip($this->channels)[$this->channels[$conn->resourceId]]]->user_name;
                    $this->verifiedUsers[$conn->resourceId] = FALSE;
                    $this->users[$conn->resourceId]->send(json_encode($MessageObject, TRUE));
                    $this->onClose($conn);
                }
                break;
        }
        if ($this->verifiedUsers[$conn->resourceId]) {
            switch ($data->ClientMessageType) {
                case "Subscribe": // USER SUBSCRIBED
                    //if (!in_array(array_flip($this->userID)[$this->userID[$conn->resourceId]], (isset(array_flip($this->channels)[$data->Channel]) ? array_flip($this->channels)[$data->Channel] : array()))) { // ONLY JOIN IF NOT ALREADY JOINED
                    $this->channels[$conn->resourceId] = $data->Channel;
                    foreach ($this->channels as $id => $channel) {
                        if ($this->channels[$conn->resourceId] == $channel) {
                            $MessageObject = new \stdClass();
                            $MessageObject->ServerMessage = TRUE;
                            $MessageObject->ServerMessageType = "GroupJoin";
                            $MessageObject->GroupName = $channel;
                            $MessageObject->Receiver = $this->userInfo[array_flip($this->channels)[$this->channels[$conn->resourceId]]]->user_name;
                            $MessageObject->Username = $this->userInfo[$conn->resourceId]->user_name;
                            $MessageObject->Fullname = $this->userInfo[$conn->resourceId]->full_name;
                            $MessageObject->Avatar = $this->userInfo[$conn->resourceId]->avatar;
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
                case "SetReceiver": // USER CLICKED ON NEW CHAT
                    foreach ($this->userInfo[$conn->resourceId]->friends as $friend) {
                        if ($data->ReceiversId === $friend->id) {
                            $this->ReceiversId[$conn->resourceId] = $data->ReceiversId;
                        }
                    }
                    break;
                case "ChatMessage": // MESSAGE RECEIVED
                    if (isset($this->channels[$conn->resourceId])) {
                        $target = $this->channels[$conn->resourceId]; // target = ALL CHANNELS TO SEND THE MESSAGE
                        foreach ($this->channels as $id => $channel) {
                            if ($channel == $target) {
                                $MessageObject = new \stdClass();
                                $MessageObject->ServerMessage = FALSE;
                                $MessageObject->GroupName = $channel;
                                if (isset($this->userInfo[array_flip($this->channels)[$this->channels[$conn->resourceId]]]->user_name)) $MessageObject->Receiver = $this->userInfo[array_flip($this->channels)[$this->channels[$conn->resourceId]]]->user_name;
                                $MessageObject->Username = $this->userInfo[$conn->resourceId]->user_name;
                                $MessageObject->Fullname = $this->userInfo[$conn->resourceId]->full_name;
                                $MessageObject->Avatar = $this->userInfo[$conn->resourceId]->avatar;
                                $MessageObject->Message = htmlspecialchars($data->Message);
                                if ($id === $conn->resourceId) {
                                    $MessageObject->WasHimself = TRUE;
                                } else {
                                    $MessageObject->WasHimself = FALSE;
                                }
                                $MessageJson = json_encode($MessageObject, TRUE);
                                $this->users[$id]->send($MessageJson);
                                $this->getHttpCode("https://beam-messenger.de/wormhole/" . file("/AccessToken.txt", FILE_IGNORE_NEW_LINES)["0"] . "/new/message/" . $this->userInfo[$conn->resourceId]->id . "/" . $this->userInfo[array_flip($this->channels)[$target]]->id . "/" . $data->Message);
                            }
                        }
                    }
                    break;
                case "GroupMessage": // GROUP MESSAGE RECEIVED -- RESERVED FOR LATER USE (CHANNEL BASED RIGHT NOW)
                    if (isset($this->channels[$conn->resourceId])) {
                        $target = $this->channels[$conn->resourceId]; // target = ALL CHANNELS TO SEND THE MESSAGE
                        foreach ($this->channels as $id => $channel) {
                            if ($channel == $target) {
                                $MessageObject = new \stdClass();
                                $MessageObject->ServerMessage = FALSE;
                                $MessageObject->GroupName = $channel;
                                if (isset($this->userInfo[array_flip($this->channels)[$this->channels[$conn->resourceId]]]->user_name)) $MessageObject->Receiver = $this->userInfo[array_flip($this->channels)[$this->channels[$conn->resourceId]]]->user_name;
                                $MessageObject->Username = $this->userInfo[$conn->resourceId]->user_name;
                                $MessageObject->Fullname = $this->userInfo[$conn->resourceId]->full_name;
                                $MessageObject->Avatar = $this->userInfo[$conn->resourceId]->avatar;
                                $MessageObject->Message = htmlspecialchars($data->Message);
                                if ($id === $conn->resourceId) {
                                    $MessageObject->WasHimself = TRUE;
                                } else {
                                    $MessageObject->WasHimself = FALSE;
                                }
                                $MessageJson = json_encode($MessageObject, TRUE);
                                $this->users[$id]->send($MessageJson);
                                $this->getHttpCode("https://beam-messenger.de/wormhole/" . file("/AccessToken.txt", FILE_IGNORE_NEW_LINES)["0"] . "/new/message/" . $this->userInfo[$conn->resourceId]->id . "/" . $this->userInfo[array_flip($this->channels)[$target]]->id . "/" . $data->Message);
                            }
                        }
                    }
                    break;
                case "TypingState": // USER STARTED TYPING
                    if (isset($this->channels[$conn->resourceId])) {
                        $target = $this->channels[$conn->resourceId];
                        foreach ($this->channels as $id => $channel) {
                            if ($channel == $target) {
                                $MessageObject = new \stdClass();
                                $MessageObject->ServerMessage = TRUE;
                                $MessageObject->ServerMessageType = "TypingState";
                                $MessageObject->GroupName = $channel;
                                if (isset($this->userInfo[array_flip($this->channels)[$this->channels[$conn->resourceId]]]->user_name)) $MessageObject->Receiver = $this->userInfo[array_flip($this->channels)[$this->channels[$conn->resourceId]]]->user_name;
                                $MessageObject->Username = $this->userInfo[$conn->resourceId]->user_name;
                                $MessageObject->Fullname = $this->userInfo[$conn->resourceId]->full_name;
                                $MessageObject->Avatar = $this->userInfo[$conn->resourceId]->avatar;
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
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        foreach ($this->clients as $client) {
            if (isset($this->channels[$conn->resourceId])) {
                $target = $this->channels[$conn->resourceId];
                foreach ($this->channels as $id => $channel) {
                    if ($channel == $target) {
                        $MessageObject = new \stdClass();
                        $MessageObject->ServerMessage = TRUE;
                        $MessageObject->ServerMessageType = "UserDisconnect";
                        $MessageObject->Username = $this->userInfo[$conn->resourceId]->user_name;
                        $MessageObject->Fullname = $this->userInfo[$conn->resourceId]->full_name;
                        $MessageObject->Avatar = $this->userInfo[$conn->resourceId]->avatar;
                        $MessageJson = json_encode($MessageObject, TRUE);
                        $this->users[$id]->send($MessageJson);
                    }
                }
            }
        }
        unset($this->verifiedUsers[$conn->resourceId]);
        unset($this->users[$conn->resourceId]);
        unset($this->channels[$conn->resourceId]);
        unset($this->userInfo[$conn->resourceId]);
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