Zagadnienia
-----------

Spis interesujących zagadnień związanych z nowoczesnym rozwojem aplikacji PHP.


Docker
======

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


