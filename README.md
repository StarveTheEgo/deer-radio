# The Deer Radio !

## Prerequisites

* Required packages:
  * docker 
  * docker-compose

Docker BuildKit must be enabled.

## Installation

* Copy **.env.example** file to **.env**
* Fill .env file, set your values for at least these variables (to make things more secure, put some different credentials):
  * APP_KEY (fill only once; you can use https://generate-random.org/laravel-key-generator to get a key) 
  * USER_UID (you can use `id -u` command to get value for this variable)
  * USER_GID (you can use `id -g` command to get value for this variable)
  * APP_ENV (set 'local' for development, and 'production' for... production)
  * APP_DEBUG (set true for development, and false for production)
  * LIQUIDSOAP_USERNAME
  * LIQUIDSOAP_EMAIL
  * LIQUIDSOAP_PASSWORD
  * DB_USERNAME
  * DB_PASSWORD
  * DB_ROOT_PASSWORD
* Also in the same .env file fill the following variables (you can use this guide - https://avflow.io/help/3844071-how-to-get-your-youtube-client-id-and-client-secret):
  * GOOGLE_CLIENT_ID
  * GOOGLE_CLIENT_SECRET
  * While creating Google Console Application, you will need to specify redirect URL:
```
https://[YOUR_DOMAIN]/admin/service-accounts/oauth-callback/google
```
Where [YOUR_DOMAIN] is... your domain! If running locally then you can use 'localhost' as domain

* Run docker compose
```bash
docker-compose up -d
```
* Add an admin user:
```bash
php artisan orchid:admin admin admin@admin.com password
```
* Open the Deer Radio admin panel: http://localhost:8080/admin/login (where instead of localhost it can be the host of your VPS)
* Enter desired settings here: http://localhost:8080/admin/settings
* Add a service account (currently - Google for YouTube livestreams)
* Add your songs
* Run, Deer Radio!
    * Currently, after the first configuration you will most likely need to restart docker application

# Temporary fix of possible permission denied issue after re-deploying

```bash
docker exec -u 0 -it deer-radio-php-fpm /bin/sh
chown -R deerdoor:deerdoor /var/radio-storage
```

# Test API request from Liquidsoap container via Curl

```bash
curl -i --header "Accept: application/json" --header "Content-Type: application/json" --header "Authorization: Bearer `cat /var/radio-storage/apiToken.bin`" http://deer-radio-nginx/api/internal/settings
```

# Google output config example:

Currently, we do not have dynamic UI to manage JSON configurations, but it will be added later.

Currently, you are supposed to use this template:
```json5
{
    "serviceAccountId": 1,
    "chatEnabled": true,
    "privacyStatus": "public"
}
```

* **serviceAccountId** - ID of service account. You need to create and connect own service account here: [host]/admin/service-accounts

# Todo list:
- Vault on production:
  - https://github.com/ahmetkaftan/docker-vault
  - https://developer.hashicorp.com/vault/tutorials/operations/production-hardening
- Output driver config encryption
- Add Icecast2 output driver
- Handle YouTube's "no longer live" situation
- Validate output config on save
- Secure connection (at least via nginx ip range check now) between app and liquidsoap
- Run the Deer Radio! Wow!
- Add new setting field types
- Configure development and production settings for doctrine proxies
- Implement a dashboard
- Implement tests of features that are missing it
- Add fields validation in Setting's seeders
- Merge LabelLink and AuthorLink into one entity
- Actualize Doctrine ORM mapping and Database schema
- Probably to restore auto-disover feature (currently it tries to access not-ready-yet hostname; Need to find out why it is not ready)
- Added authorization for both http servers (app and liquidsoap's harbor)
- Solve the mess around UnsplashSearchQueryBuilderInterface and service providers

# Roadmap:
- Finish project migration
- Get rid of Laravel ActiveRecord, use Doctrine everywhere instead
- Improve admin panel UX
- Ensure coding style and fix lack of code comments
- Integrate CI (tests, PHPStan)
- Implement importing songs feature
- Add media files tags parsing
- Automatic normalization
- Integrate self-learning AI
- Integrate and comply PHPStan
