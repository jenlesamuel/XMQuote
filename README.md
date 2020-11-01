# XMQuote App

## Prerequisite

- To sucessfully send emails, provide values to MAIL_USERNAME and MAIL_PASSWORD env variables located in docker-compose.yml.

NOTE: If gmail account credentials are used, remember to enable less secure apps for the gmail account.

## Run the Project

### Run docker-compose

```
docker-compose up
```

### Install dependencies

```
docker-compose exec app composer install
```

### Run Laravel migrations

```
docker-compose exec app php artisan migrate
```

### Run queue to process sending emails

```
docker-compose exec app php artisan queue:work
```

## Accessing the app

Visit http://localhost:8080 in your browser.
