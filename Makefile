.PHONY: test help

help: ## Aide de ce fichier
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-15s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

.DEFAULT_GOAL = help

COM_COLOR   = \033[0;34m
OBJ_COLOR   = \033[0;36m
OK_COLOR    = \033[0;32m
ERROR_COLOR = \033[0;31m
WARN_COLOR  = \033[0;33m
NO_COLOR    = \033[m

PROJECT_DIR = $(shell pwd)
TEMPLATE_DOC = responsive
APACHE_USER = www-data
DEV_USER = dev

update: ## Mise à jour des librairies composer
	composer selfupdate
	@echo -e '$(OK_COLOR)Mise à jour Composer$(NO_COLOR)'
	composer update --ignore-platform-reqs

vendor: composer.json ## Modification composer.json
	@composer selfupdate
	@echo -e '$(OK_COLOR)Mise à jour Composer$(NO_COLOR)'
	@composer update --ignore-platform-reqs

install: ## Installation du projet
	@composer install --ignore-platform-reqs

permprod: ## Permissions des dossiers et fichiers
	@echo -e '$(OK_COLOR)Permissions des dossiers et fichiers pour la production$(NO_COLOR)'
	@sudo chown -R $(APACHE_USER):$(APACHE_USER) $(PROJECT_DIR)
	@sudo find $(PROJECT_DIR) -type d -exec chmod 755 {} +
	@sudo find $(PROJECT_DIR) -type f -exec chmod 644 {} +

permdev: ## Permissions des dossiers et fichiers
	@echo -e '$(OK_COLOR)Permissions des dossiers et fichiers pour le développement$(NO_COLOR)'
	@sudo chown -R $(DEV_USER):$(DEV_USER) $(PROJECT_DIR)
	@sudo find $(PROJECT_DIR) -type d -exec chmod 755 {} +
	@sudo find $(PROJECT_DIR) -type f -exec chmod 644 {} +

code: ## Vérification et correction de la syntaxe
	@echo -e '$(OK_COLOR)Corrections syntaxiques$(NO_COLOR)'
	@./vendor/bin/phpcbf
	@echo -e '$(OK_COLOR)Tests syntaxiques$(NO_COLOR)'
	@./vendor/bin/phpcs --ignore=/home/dev/www/_lab/mycore/src/PDF/AbstractPDF.php

test: ## Lance les tests unitaires
	@echo -e '$(OK_COLOR)Tests unitaires$(NO_COLOR)'
	./vendor/bin/phpunit --coverage-html public/coverage

testcode: code ## Lance les tests unitaires en vérifiant la syntaxe du code
	@echo -e '$(OK_COLOR)Tests unitaires$(NO_COLOR)'
	@./vendor/bin/phpunit --coverage-html public/coverage

doc: code ## Génération de la documentation
	@echo -e '$(OK_COLOR)Documentation des sources$(NO_COLOR)'
	@/home/dev/www/phpdoc/./vendor/bin/phpdoc -d $(PROJECT_DIR)/src -t $(PROJECT_DIR)/public/doc --template="$(TEMPLATE_DOC)"

prepush: testcode doc ## Préparation avant un push
	@echo -e '$(OK_COLOR)Documentation des sources$(NO_COLOR)'
	@/home/dev/www/phpdoc/./vendor/bin/phpdoc -d $(PROJECT_DIR)/src -t $(PROJECT_DIR)/public/doc --template="$(TEMPLATE_DOC)"
	@git status

push: test ## Commit et Push tous les changements
	@git status
	@git add .
	@git commit -a -m "MAJ Globale"
	@git push -u origin master