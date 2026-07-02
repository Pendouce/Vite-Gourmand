#!/bin/sh

if [ ! -d vendor ]; then 
  composer install 
fi

exec  php-fpm