#! /usr/bin/env bash

sudo apt-get update

sudo apt-get -y install git apache2 php-xml libapache2-mod-php php php-mysql curl php-curl zip unzip mysql-client php-gd

cd /home/ubuntu

sudo systemctl enable apache2
sudo systemctl start apache2

sudo git clone git@github.com:illinoistech-itm/jpatel74.git

sudo rm -r /var/www/html/*

sudo mv /home/ubuntu/jpatel74/website/* /var/www/html
sudo mv /var/www/html/password.php /var/www

cd /var/www/html

sudo curl -sS https://getcomposer.org/installer | sudo php
sudo php composer.phar require aws/aws-sdk-php

sudo touch /var/spool/cron/root

#write out current crontab
crontab -l > mycron
#echo new cron into cron file
echo "1 * * * * php /var/www/html/edit.php" >> mycron
#install new cron file
crontab mycron
rm mycron
