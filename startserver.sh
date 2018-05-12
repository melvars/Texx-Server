#!/usr/bin/env bash
cd main/app/sprinkles/core/assets/SiteAssets/php/Chatserver/bin/

if [ "$1" = "persistent" ]
    then
        nohup php WebChatServer.php &
else
        php WebChatServer.php
fi
