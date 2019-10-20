# Drupal middle-format migration

To install the migration tool run (requires PHP 5.6+):

```bash
composer install
```

To setup test Drupal 7 site run:

```bash
composer create-project drupal-composer/drupal-project:7.x-dev site --no-interaction
```

This will build a test Drupal 7 site under `./site`.

After that start Docker containers by running:

```bash
docker-compose up -d
```

Your site will be available at: http://drupal.docker.localhost:8000

Proceed with installing the standard profile using the following database configuration:

```
database: drupal
username: drupal
password: drupal
host: mariadb
```

For list of available commands run inside the container:

```bash
docker-compose exec php ./bin/console
```

To export content run:

```bash
docker-compose exec php ./bin/console export node article
```

Content will be exported in `./content`.

Change content export configuration by editing `./parameters.yml`. 

