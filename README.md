For running this service you need to have docker and docker-compose installed on your computer.

Docker installation guide:

https://docs.docker.com/engine/install/ubuntu/

Docker-compose instalation guide:

https://docs.docker.com/compose/install/

Next step. 

You need to fill in .env file. In this case, you can copy .env.example with .env name.

Third step. Running containers.

Command:

```shell
docker-compose up -d
```
A container name conflict error may occur at startup.

In the case of an error with database container - rename it by changing the MYSQL_HOST parameter in the .env file.

In the case of an error with PHP container - rename it by changing the PHP_CONTAINER parameter in the .env file.

Fourth step. You need to visit PHP container's sh. 
You can use your IDE functionality or shell. Command example for shell:

```shell
docker exec -it kosoturov_symfony_test sh
```
In case if you change PHP_CONTAINER in the .env file, you will also need to use new name instead "kosoturov_symfony_test".

All next actions are needed to be done in the PHP container:

First step - install dependencies, command:

```shell
composer install
```
Second step - DB migration, command:

```shell
php bin/console doctrine:migrations:migrate
```
For running import, you need to put file with data into project files. 
As example, I left file text.csv in the core of the project.
Command for running data import with example file:
 
```shell
 php bin/console products:import test.csv
 ```
Command for running unit tests:
```shell
 php bin/phpunit 
```
