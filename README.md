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
- Add new setting field types
- Migrate song feature
- Migrate Youtube API feature
- Migrate console commands
- Add liquidsoap container with its script
- Implement a dashboard
- Update readme
- Implement tests of features that are missing it
- Add fields validation in Setting's seeders

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
