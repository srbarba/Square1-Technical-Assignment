# Square1-Technical-Assignment
This is a project developed for Square 1 technical assignment.

It's done with Laravel + Vue.

## Installation
Load composer packages and modules:

```bash
$ composer install
```

```bash
$ npm install
```

Once don npm insta, you have to build the resources with:

```bash
$ npm run dev
```
or

```bash
$ yarn dev
```

## Database
In database folder you will see a database.sqlite file.
I added this file with some imported products to rapid testing.

If you prefer to start from the beginning, just execute:
```bash
$ php artisan migrate:refresh
```
Remember reimport the data before to start testing.

## Import data
With dependencies installed you need to import some products.
You can import products from AppliancesDelivered.ie with two options.
- Import directly with one command: (this process can get about 15 minutes ALL THE DATA)
```bash
$ php artisan import:all
```
- Import simulating cron schedule: (this process can get about 45 minutes to import ALL THE DATA)
```bash
$ composer cron
```

In production, with a server working with cron, you only will set a cronjob each minute with that executes the command:
```bash
$ php artisan cron:schedule
```
You can check how it works at file /app/Console/Cron/Schedule.php

## Usage
Execute the command below to test the project:
```bash
$ composer dev
```

Once you finished, remember to close the process from command line.

## Requirements
You need composer, npm and SQLite to execute this project.
