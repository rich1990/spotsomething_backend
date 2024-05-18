# Spot a Room frontend

Sinfony microservice that process huge json file
## Installation

Define your database connection in the .env file

```bash
composer install
```
run the migrations

```bash
php bin/console doctrine:migrations:migrate
```

import data from the json file

```bash
php bin/console app:sync-flats 
```
is going to take long time, stop it when you have enough data :)

## Configuring with frontend 

Make sure you use the same API_KEY inside server.js inside

```bash
config/packages/api.yaml
```
