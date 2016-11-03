#! /usr/bin/env bash

apt-get update
apt-get -y install git apache2 php-xm php php-mysql curl php-curl zip unzip

cd /home/ubuntu

curl -sS https://getcomposer.org/installer | php

php composer.phar require aws/aws-sdk-php

systemctl enable apache2
systemctl start apache2

git clone https://github.com/jjp1023/boostrap-website.git

mv /home/ubuntu/boostrap-website/* /var/www/html
