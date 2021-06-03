compose-bash:
	docker-compose run --rm app bash

compose-install:
	docker-compose run --rm app make install

install:
	composer install

compose-start:
	docker-compose up -d

start:
	php -S 0.0.0.0:8080 index.php

lint:
	@echo "RUN LINTER!!!!"

ci:
	docker-compose build
	docker-compose -f docker-compose.yml -p weather-app-ci run --rm app make install
	docker-compose -f docker-compose.yml -p weather-app-ci up
