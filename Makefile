


.DEFAULT_GOAL = help

## —— 🎵 🐳 Makefile 🐳 🎵 ——————————————————————————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9\./_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'


main: ## Start app
	symfony server:start


git:
	git add . 
	git commit -m "$(m)"
	git push

clean:
	php bin/console cache:clear

migrate:
	php bin/console doctrine:migrations:migrate

table:
	php bin/console make:entity



check: 
	php bin/console