


.DEFAULT_GOAL = help


main: ## Start app
	symfony server:start

# ## —— 🎵 🐳 Makefile 🐳 🎵 ——————————————————————————————————————————————————————
# help: ## Outputs this help screen
# 	@grep -E '(^[a-zA-Z0-9\./_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'


git:
	git add . 
	git commit -m "$(m)"
	git push

clean:
	php bin/console cache:clear

migrate:
	php bin/console doctrine:migrations:migrate

migration:
	php bin/console make:migration

table:
	php bin/console make:entity

check: 
	php bin/console

route:
	php bin/console debug:router
