#!/bin/bash -e
redis-server &
PIDS[0]=$!
node resources/assets/js/chatServer.js &
PIDS[1]=$!
php artisan queue:work & 
PIDS[2]=$!
php artisan serve &
PIDS[3]=$!
npm run watch &
PIDS[4]=$!

trap "kill ${PIDS[*]}" SIGINT

wait