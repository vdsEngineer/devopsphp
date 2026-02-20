setup:
	docker compose up -d --build
	docker compose exec app composer install
	docker compose exec app npm install
	docker compose exec app php artisan migrate

test:
	docker compose exec app php artisan test

analyze:
	docker compose exec app vendor/bin/phpstan analyse

pint:
	docker compose exec app vendor/bin/pint

shell:
	docker compose exec app sh