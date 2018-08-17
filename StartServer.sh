#!/bin/bash -e
redis-server &
PIDS[0]=$!
sudo php artisan serve --host 0.0.0.0 --port 80 &
PIDS[1]=$!

trap "kill ${PIDS[*]}" SIGINT

wait
