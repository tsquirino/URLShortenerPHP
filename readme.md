# URLShortenerPHP

Web system for creating short URLs. Currently hosted on [http://urlshortenerphp-env.eba-jssdd4kw.sa-east-1.elasticbeanstalk.com].

## Running instructions

This API can be run using Laradock in any local environment with docker and docker-compose installed.

The following command must be run in the project's root path to get containers with a Nginx server and a MySQL database running:

```
docker-compose up -d nginx mysql
```

'php artisan' commands (such as other commands) can be run in the workspace container automatically created:

```
docker-compose exec workspace bash
```

Before using the API locally, one must run the available migrations in order to setup the database table for managing URLs:

```
php artisan migrate
```

The version currently running on [http://urlshortenerphp-env.eba-jssdd4kw.sa-east-1.elasticbeanstalk.com] was deployed using AWS Elastic Beanstalk.

## Endpoints

A detailed description of the public API endpoints can be found in the OpenAPI-generated
[documentation page](http://urlshortenerphp-env.eba-jssdd4kw.sa-east-1.elasticbeanstalk.com/api/documentation).

## Front-end

The home page of the project consists of an offset-paginated view of the existing URLs in database. User must be
authenticated to see the list, otherwise they will be redirected to a login page.

Credentials for accessing the URL list:
* Username: admin
* Password: 123456

## File structure

The whole system (front-end and back-end) was developed in PHP, using the Laravel framework. URLs are kept in a single table of a
MySQL database.

* /laradock: Contains docker files for builiding the containers necessary to emulate the infrastructure of a server with Nginx, PHP and MySQL.
* /src: Contains the source code of the Laravel project.
* docker-compose.yaml: Contains instructions for docker-compose to build and run docker containers.
* readme.md: This file.