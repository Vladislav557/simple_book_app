#!/bin/bash
NETWORK_NAME="172.10.0.0"
docker network inspect "$NETWORK_NAME" >/dev/null 2>&1
if [ $? -ne 0 ]; then
    docker network create --subnet 172.10.0.0/16 "$NETWORK_NAME"
fi