# Biblio

Biblio is a fictitious study project based on the GoogleBooks API, to create a reading management application like TVShowTime for films and series

## Project recovery

```bash
composer install
```

Create a .env.local file in the project root
add access to the database
Then create the database locally

```bash
bin/console doctrine:database:create
```

Then apply the migrations

```bash
bin/console doctrine:migrations:migrate
```

Check the database schema

```bash
bin/console doctrine:schema:validate
```

## Start the development server

```bash
php -S 127.0.0.1:8000 -t public
```
