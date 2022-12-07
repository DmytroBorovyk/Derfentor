# Setup instructions

- run `git clone ...`

open IDE Project from existing files in this directory
- `cp .env.example .env`
- set DB_PASSWORD in .env
- `docker compose up -d`
  
Wait until build.

- Inside container (see below)
  - run `php artisan optimize:clear`
  - make sure that there is no key
  - run `php artisan key:generate`
  - run `php artisan migrate`
  - run `php artisan optimize`
  
## to open php container
run `docker exec -it derfentor-local-api bash`

## to generate documentation
run `php artisan l5-swagger:generate` in php container. Documentation will be available by uri `/api/documentation`
