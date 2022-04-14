#!/bin/bash

echo start

ip=101.132.178.141

zip=main.tar.gz

name=vue-nuxt

echo build

npm run build

echo connect

tar czvf - $zip .nuxt static nuxt.config.js package_production.json | ssh root@$ip "cd /usr/local/nginx/html/vue-nuxt/; tar xzvf -; mv package_production.json package.json; pm2 delete $name; pm2 start npm --name $name -- run start;"

echo end
