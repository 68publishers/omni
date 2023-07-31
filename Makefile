stop:
	docker compose stop

start:
	docker compose up -d

down:
	docker compose down

cs.fix:
	PHP=81 make composer.update
	docker exec 68publishers.omni.81 vendor/bin/php-cs-fixer fix -v

cs.check:
	PHP=81 make composer.update
	docker exec 68publishers.omni.81 vendor/bin/php-cs-fixer fix -v --dry-run

composer.update:
ifndef PHP
	$(error "PHP argument not set.")
endif
	@echo "========== Installing dependencies with PHP $(PHP) ==========" >&2
	docker exec 68publishers.omni.$(PHP) composer update --no-progress --prefer-dist --prefer-stable --optimize-autoloader --quiet

composer.update-lowest:
ifndef PHP
	$(error "PHP argument not set.")
endif
	@echo "========== Installing dependencies with PHP $(PHP) (prefer lowest dependencies) ==========" >&2
	docker exec 68publishers.omni.$(PHP) composer update --no-progress --prefer-dist --prefer-lowest --prefer-stable --optimize-autoloader --quiet
