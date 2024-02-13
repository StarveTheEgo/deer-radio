# The Deer Radio !

* Copy **.env.example** file to **.env**
* Run docker compose
```bash
docker-compose up -d -f docker-compose-production.yml
```
* Add an admin user:
```bash
php artisan orchid:admin admin admin@admin.com password
```
* Open the Deer Radio admin panel: http://localhost:81/admin/login
* Enter desired settings here: http://localhost:81/admin/settings
* Add your songs
* Run the Deer Radio! (deer radio script is not ready yet)

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
