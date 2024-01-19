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

# Todo list:
- Implement edit/delete functionality for ServiceAccount screens
- Configure Laravel cron task scheduler
- Implement access token refreshing script and schedule it
- Add validation that does not allow to create multiple access tokens for the same 3rd party account
- Add new setting field types
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
