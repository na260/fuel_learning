#!/bin/sh

## apache conf
# 下記追加

sed -i -e "s|/home/site/site1|/home/site/site1/prj01/public|" /etc/apache2/sites-available/vhost1.conf

sed -i -e "22i <Directory /home/site/site1/prj01/public>" /etc/apache2/sites-available/vhost1.conf
sed -i -e "23i     AllowOverride All" /etc/apache2/sites-available/vhost1.conf
sed -i -e "24i     Require all granted" /etc/apache2/sites-available/vhost1.conf
sed -i -e "25i     DirectoryIndex index.php" /etc/apache2/sites-available/vhost1.conf
sed -i -e "26i </Directory>" /etc/apache2/sites-available/vhost1.conf

systemctl restart php7.4-fpm apache2

# fuel migrate
mv /home/site/site1/prj01/fuel/app/config/development/migrations.php /home/site/site1/prj01/fuel/app/config/development/migrations.php.bak
cd /home/site/site1/prj01
php oil refine migrate
