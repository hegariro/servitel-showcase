# Variables
DOCKER_COMPOSE = docker compose -f deploy/docker-compose.yml
PHP_CONT = laravel_app
DB_SERVICE = db
APP_SERVICE = app

.PHONY: help build up up-db down restart ps logs shell get-token migrate seed migrate-fresh redeploy-app

help: ## Muestra este mensaje de ayuda
	@awk 'BEGIN {FS = ":.*##"; printf "\nUso:\n  make \033[36m<comando>\033[0m\n"} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2 }' $(MAKEFILE_LIST)

build: ## Construye las imágenes de los contenedores
	$(DOCKER_COMPOSE) build

up: ## Levanta los servicios en segundo plano
	$(DOCKER_COMPOSE) up -d

up-db: ## Levanta unicamente el servicio de base de datos
	$(DOCKER_COMPOSE) up -d $(DB_SERVICE)

down: ## Detiene y elimina los contenedores
	$(DOCKER_COMPOSE) down

restart: ## Reinicia los servicios
	$(DOCKER_COMPOSE) restart

redeploy-app: ## Reconstruye y reinicia solo el contenedor de la aplicacion
	$(DOCKER_COMPOSE) up -d --build $(APP_SERVICE)

ps: ## Lista los contenedores activos
	$(DOCKER_COMPOSE) ps

logs: ## Muestra los logs en tiempo real
	$(DOCKER_COMPOSE) logs -f

shell: ## Entra a la terminal del contenedor de Laravel
	$(DOCKER_COMPOSE) exec app sh

# Genera un token para el usuario admin y lo muestra en consola
get-token: ## Genera un token de acceso para el usuario admin
	@$(DOCKER_COMPOSE) exec $(APP_SERVICE) php artisan tinker --execute="echo App\Models\User::where('email', 'admin@servitel.dev')->first()->createToken('postman-token')->plainTextToken"

migrate: ## Ejecuta las migraciones de base de datos
	$(DOCKER_COMPOSE) exec app php artisan migrate

seed: ## Ejecuta los seeders de la base de datos
	$(DOCKER_COMPOSE) exec app php artisan db:seed

migrate-fresh: ## Borra todo y vuelve a ejecutar migraciones y seeders
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) php artisan migrate:fresh --seed

init: build up ## Inicializa el proyecto por primera vez (build + up)
	@echo "Esperando a que la BD esté lista..."
	@sleep 5
	$(DOCKER_COMPOSE) exec app php artisan key:generate
	$(DOCKER_COMPOSE) exec app php artisan migrate