# The Deer Radio !

* Copy **.env.example** file to **.env**
* Run docker compose
```bash
docker-compose up -d
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

# Todo list:
- Vault on production:
  - https://github.com/ahmetkaftan/docker-vault
  - https://developer.hashicorp.com/vault/tutorials/operations/production-hardening
- Secure connection (at least via nginx ip range check now) between app and liquidsoap
- Actualize outputs due to recently added service accounts implementation
- Implement a way of sharing outputs data to liquidsoap
- Run the Deer Radio! Wow!
- Add new setting field types
- Configure development and production settings for doctrine proxies
- Migrate Youtube API feature
- Implement a dashboard
- Update readme
- Implement tests of features that are missing it
- Add fields validation in Setting's seeders
- Merge LabelLink and AuthorLink into one entity
- Actualize Doctrine ORM mapping and Database schema
- Remove very specific code related to routing/menu elements from AbstractScreenFilter
- Probably to restore auto-disover feature (currently it tries to access not-ready-yet hostname; Need to find out why it is not ready)
- Added authorization for both http servers (app and liquidsoap's harbor)
- Actualize video encoders (let only ffmpeg)
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
