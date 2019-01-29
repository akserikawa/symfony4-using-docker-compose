# Create a Symfony 4 app inside Docker containers
Follow this guide to run a Symfony 4 app inside Docker containers using Docker Compose.

# Installation
First, clone this repo:
```bash
git clone https://github.com/eko/docker-symfony
```
(it comes with PHP7, MySQL, nginx and ELK as services)

Once you've cloned the repo, add this to your `/etc/hosts` file:
```
127.0.0.1 symfony.localhost 
```
To run a Symfony 4 project inside a Docker container, your `docker-compose.yml` file should look like this:
```
version: '3.7'
services:
    db:
        image: mysql
        command: ["--default-authentication-plugin=mysql_native_password"]
        ports:
            - "33060:3306" # we use the port 33060 on our host machine to avoid conflicts with MySQL from host
        environment:
            MYSQL_ROOT_PASSWORD: root_password
            MYSQL_DATABASE: db_name
            MYSQL_USER: db_user
            MYSQL_PASSWORD: db_pass
    php:
        build: ./php-fpm
        ports:
            - "9000:9001"
        volumes:
            - ./symfony:/var/www/symfony:cached # our project lives in ./symfony, but it could be anywhere in your system
            - ./logs/symfony:/var/www/symfony/var/log:cached
        links:
            - db
        extra_hosts:
            - "docker-host.localhost:127.0.0.1"
    nginx:
        build: ./nginx
        ports:
            - "8080:80" # again, we use the port 8080 on our host machine because we're already running nginx natively on port 80
        links:
            - php
        volumes:
            - ./logs/nginx:/var/log/nginx:cached
            - ./symfony:/var/www/symfony:cached
    elk:
        image: willdurand/elk
        ports:
            - "81:80"
        volumes:
            - ./elk/logstash:/etc/logstash:cached
            - ./elk/logstash/patterns:/opt/logstash/patterns:cached
            - ./logs/symfony:/var/www/symfony/var/log:cached
            - ./logs/nginx:/var/log/nginx:cached
            - ./logs/nginx:/var/log/nginx:cached
            - ./logs/nginx:/var/log/nginx:cached
            - ./logs/nginx:/var/log/nginx:cached               
```

Notice how we've mapped our host machine ports 33060 and 8080 to 3360 (MySQL) and 80 (nginx) respectively. 
This is to avoid conflicts with our host machine services.

# Symfony installation and setup
Inside your newly created docker-symfony folder run:
```bash
composer create-project symfony/website-skeleton symfony
```
Once it's installed we need to configure our `DATABASE_URL` in our .env file:
```
DATABASE_URL=mysql://db_user:db_pass@db/db_database
```
Pay attention to **@db** as this is the name of the database service defined in our docker-compose.yml 

Finally, run:
```
docker-compose up
```
You are done, you can visit your Symfony application on the following URL: http://symfony.localhost:8080 (and access Kibana on http://symfony.localhost:81)

## Important notes
You can rebuild all your Docker images by running:
```
docker-compose build
```
To use php commands inside our container, run:
```
docker-compose run php bin/console ...
```
To list all the containers that are running (you should see all four containers):
```
docker-compose ps
```
You can also start/stop all the containers:
```
docker-compose start
docker-compose stop
```

Big shout out to Vincent Composieux aka eko https://github.com/eko
