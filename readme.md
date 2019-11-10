# Overview
This project is an small e-commerce done in a short time show the my abilities with PHP(Laravel) and JS (Vue). 

The result is a proposal of a simple e-commerce with a list of products and categories, the capacity to sort the list, basic user signup/signin and wishlist for user loged.

You can see the original bases of the practice in the root folder of the project:
[Square_1_Technical_Assignment](Square_1_Technical_Assignment.pdf) (BE)
[Square 1 Front End Developer Assignment](Square_1_Front_End_Developer_Assignment.pdf) (FE)

## Prerequisites
You need PHP 7, composer, npm and SQLite installed in your computer to execute this project.

## Quick execution

##### Setup
To quick setup you need to execute from bash shell on root project foolder:
```bash
$ ./setup.sh
```
This script will check if you have all the prerequisites installed and will install all the PHP and NPM dependecies.

##### Start

To start the project you need to execute from bash shell on root project foolder:
```bash
$ ./start.sh
```
This script will execute php artisan serve with argumments and open the urle in your browser automatically.
The project will be executed at localhost:8000 by default.
Alternatively, you can use another host or port passing options "-h" or "-p" as arguments:
```bash
$ ./start.sh -p 8080

or

$ ./start.sh -h 127.0.0.1 -p 8080
```
## Slow execution

If you prefer, you can explore all the setps for the setup and execution manually.

### Installation
Load composer packages and modules:

```bash
$ composer install
```

```bash
$ npm install
```

Once done npm install, you have to build the resources with:

```bash
$ npm run dev
```
or

```bash
$ yarn dev
```

### Database
In database folder you will see a database.sqlite file.
I added this file with some imported products to rapid testing.

If you prefer to start from the beginning, just execute:
```bash
$ php artisan migrate:refresh
```
Remember reimport the data before to start testing.

### Import data
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

### Usage
Execute the command below to test the project:
```bash
$ composer dev
```

Once you finished, remember to close the process from command line.
