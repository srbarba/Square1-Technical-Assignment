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
$ npm run dev
```
or

```bash
$ yarn
$ yarn dev
```

## Import data
With dependencies installed you need to import some products.
You can import products from AppliancesDelivered.ie with two options.
- Import directly with one command:
```bash
$ php artisan import:all
```
- Import simulating cron schedule:
```bash
$ composer cron
```

## Usage
Execute the command below to test the project:
```bash
$ composer dev
```

Once you finished, remember to close the process from command line.

## Requirements
You need composer, npm and SQLite to execute this project.
