#!/bin/bash

export DEBIAN_FRONTEND=noninteractive
echo "Provisioning virtual machine..."
sudo apt-get update -y > /dev/null
# Git
echo "Installing Git"
sudo apt-get install git -y > /dev/null

# Nginx
echo "Installing Apache"
sudo apt-get install apache2 -y >/dev/null



echo "Installing PHP"
sudo apt-get install php5 php5-cli -y > /dev/null

echo "Installing PHP extensions"
sudo apt-get install curl php5-curl php5-gd php5-mcrypt php5-mysql -y > /dev/null

# MySQL
echo "Preparing MySQL"
sudo apt-get install debconf-utils -y > /dev/null
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password 1234"
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password 1234"

echo "Installing MySQL"
sudo apt-get install mysql-server -y > /dev/null


sudo usermod -a -G vagrant www-data > /dev/null
sudo chmod 755 /var/www
# Restart Nginx for the config to take effect
#configure apache2
VHOST=$(cat <<EOF
<VirtualHost *:80>
    DocumentRoot "/var/www/"
    <Directory "/var/www/">
         Options Indexes FollowSymLinks
    	 AllowOverride All
         Order allow,deny
         Allow from all
    </Directory>
</VirtualHost>
EOF
)
echo "${VHOST}" > /etc/apache2/sites-available/000-default.conf

# enable mod_rewrite
sudo a2enmod rewrite
# restart apache


#create db
sudo mysql -u root -p1234 -e "create database aod";

sudo apt-get install phpmyadmin php-mbstring php-gettext

sudo phpenmod mcrypt
sudo phpenmod mbstring
sudo service apache2 restart
echo "Finished provisioning."
