#!/bin/bash

echo start

ip=101.132.178.141

zip=main.tar.gz

name=vue-nuxt

echo build

npm run build

echo connect

tar czvf - $zip .nuxt static server nuxt.config.js package.json | ssh root@$ip "cd /usr/local/nginx/html/vue-nuxt/; tar xzvf -; pm2 restart $name;"

echo end
