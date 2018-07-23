#!/bin/bash -e
redis-server &
PIDS[0]=$!
php artisan serve &
PIDS[1]=$!
npm run watch &#> /dev/null 2>&1
PIDS[2]=$!

trap "kill ${PIDS[*]}" SIGINT

wait
