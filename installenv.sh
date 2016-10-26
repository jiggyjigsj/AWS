#! /usr/bin/env bash

sudo apt-get update
sudo apt-get -y install git 
sudo apt-get -y install apache2

sudo systemctl enable apache2
sudo systemctl start apache2

cd /home/ubuntu

sudo git clone https://github.com/jjp1023/boostrap-website.git

sudo mv /home/ubuntu/boostrap-website/* /var/www/html