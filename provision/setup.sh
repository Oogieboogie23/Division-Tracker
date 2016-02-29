#!/bin/bash

export DEBIAN_FRONTEND=noninteractive
echo "Provisioning virtual machine..."
sudo apt-get update -y > /dev/null
# Git
echo "Installing Git"
sudo apt-get install git -y > /dev/null

# Nginx
echo "Installing Nginx"
sudo apt-get install nginx -y > /dev/null


echo "Installing PHP"
sudo apt-get install php5-common php5-dev php5-cli php5-fpm -y > /dev/null

echo "Installing PHP extensions"
sudo apt-get install curl php5-curl php5-gd php5-mcrypt php5-mysql -y > /dev/null

# MySQL
echo "Preparing MySQL"
sudo apt-get install debconf-utils -y > /dev/null
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password 1234"
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password 1234"

echo "Installing MySQL"
sudo apt-get install mysql-server -y > /dev/null

# Nginx Configuration
echo "Configuring Nginx"
sudo cp /vagrant/provision/nginx_vhost /etc/nginx/sites-available/nginx_vhost > /dev/null
sudo ln -s /etc/nginx/sites-available/nginx_vhost /etc/nginx/sites-enabled/

sudo rm -rf /etc/nginx/sites-available/default

sudo usermod -a -G vagrant www-data > /dev/null
sudo chmod 755 /var/www
# Restart Nginx for the config to take effect
sudo service nginx restart > /dev/null

echo "Finished provisioning."
