#!/bin/bash -e
redis-server &
PIDS[0]=$!
php artisan serve --host=192.168.0.59 &
PIDS[1]=$!

trap "kill ${PIDS[*]}" SIGINT

wait
