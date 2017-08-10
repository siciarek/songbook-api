Zagadnienia
-----------

Spis interesujących zagadnień związanych z nowoczesnym rozwojem aplikacji PHP.


Zrzut tylko danych:
===================

..code-block:: bash

    $ mysqldump -h0.0.0.0 -uroot -proot --no-create-info songbook > dump.sql


Docker
======

    $ docker pull fromsmash/php7-fpm-symfony3
    $ docker images fromsmash/php7-fpm-symfony3
    $ docker run -i -t fromsmash/php7-fpm-symfony3:latest /bin/bash

    -w . - working directory

    * http://laradock.io/

sudo usermod -aG docker siciarek

ln -s ./web public

docker-compose up -d apache2 mysql

sudo docker-compose exec workspace bash



Delete all containers

docker rm $(docker ps -a -q)

Delete all images

docker rmi $(docker images -q)
for IMG in `docker images -q`; do docker rmi -f $IMG ;done


