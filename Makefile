#Makefile

help:
	@echo "Makefile permettant d'installer le projet en une commande, utiliser 'make install', configurer le lien de la base de données dans un fichier .env.local avant"

install:
	composer install --optimize-autoloader
	php bin/console doctrine:database:create
	php bin/console doctrine:database:create --env=test
	php bin/console doctrine:schema:update --force
	php bin/console doctrine:schema:update --force --env=test
	php bin/console doctrine:fixtures:load
	php bin/console doctrine:fixtures:load --env=test
	php bin/console doctrine:migrations:migrate latest --no-interaction
	php bin/console c:c
	vendor/bin/grumphp run

composer-install:
	composer install --no-dev --optimize-autoloader

fixtures:
	symfony console doctrine:fixtures:load

setup-database:
	symfony console doctrine:database:create

schema-update:
	symfony console doctrine:schema:update --force

cache-clear:
	symfony console c:c
