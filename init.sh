#!/bin/bash

docker-compose exec php php artisan migrate:refresh

exit;
