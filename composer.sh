#!/bin/bash

docker run --rm \
     -u "$(id -u):$(id -g)" \
     -v "$(pwd):/var/www/html" \
     -w /var/www/html composer/composer \
     composer ${1}