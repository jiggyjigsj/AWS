#! /usr/bin/env bash

apt-get update
apt-get -y install git apache2 php-xml libapache2-mod-php php php-mysql curl php-curl zip unzip

cd /home/ubuntu

curl -sS https://getcomposer.org/installer | php

php composer.phar require aws/aws-sdk-php

systemctl enable apache2
systemctl start apache2

mkdir /var/www/html/vendor
mv vendor/* /var/www/html/vendor

mv /home/ubuntu/jpatel74/website/* /var/www/html
mv /var/www/html/password.php /var/www
