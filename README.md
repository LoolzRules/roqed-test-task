# ROQED test task

To run the project:
1. copy env config: `cp .env.example .env`
1. to avoid permission conflicts:
   1. make vendor folder `mkdir vendor`
   1. make uploads folder: `mkdir -p ./storage/app/public/uploads`
   1. make previews folder: `mkdir -p ./storage/app/public/previews`
   1. modify storage permissions: `chmod -R 775 ./storage`
1. install backend dependencies using small docker image: `docker run --rm \
   -u "$(id -u):$(id -g)" \
   -v "$(pwd):/var/www/html" \
   -w /var/www/html \
   laravelsail/php82-composer:latest \
   composer install --ignore-platform-reqs`
1. create containers: `./vendor/bin/sail up -d`
1. link the storage: `./vendor/bin/sail artisan storage:link`
1. migrate the db: `./vendor/bin/sail artisan migrate`
1. seed the db with data: `./vendor/bin/sail artisan db:seed`
1. install frontend dependencies: `./vendor/bin/sail npm install`
1. build the frontend: `./vendor/bin/sail npm run build`
1. open localhost

Please contact me if you encounter any problems
